<?php

class Course {

	private $course_name;
	private $course_description;
	private $course_image_link;
	
	function __construct($course_name, $course_description, $course_image_link, $id = null) {
		$this->course_name = $course_name;
		$this->course_description = $course_description;
		$this->course_image_link = $course_image_link;
		if (!is_null($id)) {
			$this->id = $id;
		}
	}

	public function save() {
		$stmt = DB::getConnection()->prepare("
			INSERT INTO courses (name, description, image_link)
			VALUES (:course_name, :course_description, :course_image_link)
		");
		$stmt->bindParam(':course_name', $this->course_name, PDO::PARAM_STR);
		$stmt->bindParam(':course_description', $this->course_description, PDO::PARAM_STR);
		$stmt->bindParam(':course_image_link', $this->course_image_link, PDO::PARAM_STR);
		$stmt->execute();

		$course_id = DB::getConnection()->lastInsertId();
		$this->course_id = $course_id;

		return $course_id;
	}

	public static function getAll() {
		return DB::getConnection()->query("
			SELECT id, name, description, image_link 
			FROM courses
			");
	}

	public static function getOne($id) {
		$stmt = DB::getConnection()->prepare("
			SELECT id, name, description, image_link
			FROM courses 
			WHERE id = :id
			LIMIT 1 
		");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();

		return $result;
	}

	public static function findStudents($id) {
		$stmt = DB::getConnection()->prepare("
			SELECT enrollment.student_id
			FROM courses 
			INNER JOIN enrollment ON courses.id = enrollment.course_id
			WHERE courses.id = :id
		");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();

		return $result;
	}

	public function update($course_id, $course_name, $course_description, $course_image_link) {
		$stmt = DB::getConnection()->prepare("
			UPDATE courses
			SET name = :course_name, description = :course_description, image_link = :course_image_link
			WHERE id = :course_id
		");
		$stmt->bindParam(':course_id', $course_id, PDO::PARAM_STR);
		$stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR);
		$stmt->bindParam(':course_description', $course_description, PDO::PARAM_STR);
		$stmt->bindParam(':course_image_link', $course_image_link, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->errorCode() !== '00000') {
			die($stmt->errorInfo()[0]);
		}
	}


	public function saveToEnrollment($student_id, $course_id) {
		$stmt = DB::getConnection()->prepare("
			INSERT INTO enrollment (student_id, course_id)
			VALUES (:student_id, :course_id)
		");
		if ($stmt->errorCode()) {
			die($stmt->errorInfo()[0]);
		}
		$stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
		$stmt->bindParam(':course_id', $course_id, PDO::PARAM_STR);
		$stmt->execute();
	}

	public function removeFromEnrollment($course_id) {
		$stmt = DB::getConnection()->prepare("
			DELETE FROM enrollment
			WHERE course_id = :course_id
		");
		if ($stmt->errorCode()) {
			die($stmt->errorInfo()[0]);
		}
		$stmt->bindParam(':course_id', $course_id, PDO::PARAM_STR);
		$stmt->execute();
	}

	public function delete($course_id) {
		$stmt = DB::getConnection()->prepare("
			DELETE FROM courses
			WHERE id = :course_id
		");
		$stmt->bindParam(':course_id', $course_id, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->errorCode() !== '00000') {
			die($stmt->errorInfo()[0]);
		}
	}

}