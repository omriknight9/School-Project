<?php

class Student {

	private $student_id;
	private $signed_in;
	private $student_name;
	private $student_phone;
	private $student_email;
	private $student_image_link;
	
	function __construct($student_name, $student_phone,$student_email, $student_image_link, $student_id = null) {
		$this->student_name = $student_name;
		$this->student_phone = $student_phone;
		$this->student_email = $student_email;
		$this->student_image_link = $student_image_link;
		if (!is_null($student_id)) {
			$this->student_id = $student_id;
		}
	}

	public function is_signed_in() {
		return $this->signed_in;
	}

	public function save() {
		$stmt = DB::getConnection()->prepare("
			INSERT INTO students (name, phone, email, image_link)
			VALUES (:student_name, :student_phone, :student_email, :student_image_link)
		");
		if ($stmt->errorCode()) {
			die($stmt->errorInfo()[0]);
		}
		$stmt->bindParam(':student_name', $this->student_name, PDO::PARAM_STR);
		$stmt->bindParam(':student_phone', $this->student_phone, PDO::PARAM_STR);
		$stmt->bindParam(':student_email', $this->student_email, PDO::PARAM_STR);
		$stmt->bindParam(':student_image_link', $this->student_image_link, PDO::PARAM_STR);
		$stmt->execute();

		$student_id = DB::getConnection()->lastInsertId();
		$this->student_id = $student_id;

		return $student_id;
	}


	public static function getAll() {
		return DB::getConnection()->query("
			SELECT id, name, phone, email, image_link 
			FROM students
			");
	}

	public static function getOne($id) {
		$stmt = DB::getConnection()->prepare("
			SELECT id, name, phone, email, image_link
			FROM students 
			WHERE id = :id
			LIMIT 1 
		");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();

		return $result;
	}

	public static function findCourses($id) {
		$stmt = DB::getConnection()->prepare("
			SELECT enrollment.course_id
			FROM students 
			INNER JOIN enrollment ON students.id = enrollment.student_id
			WHERE students.id = :id
		");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll();

		return $result;
	}

	public function update($student_id, $student_name, $student_phone, $student_email, $student_image_link) {
		$stmt = DB::getConnection()->prepare("
			UPDATE students
			SET name = :student_name, phone = :student_phone, email = :student_email, image_link = :student_image_link
			WHERE id = :student_id
		");
		
		$stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
		$stmt->bindParam(':student_name', $student_name, PDO::PARAM_STR);
		$stmt->bindParam(':student_phone', $student_phone, PDO::PARAM_STR);
		$stmt->bindParam(':student_email', $student_email, PDO::PARAM_STR);
		$stmt->bindParam(':student_image_link', $student_image_link, PDO::PARAM_STR);
		$stmt->execute();
		// if (empty($student_name)) {
		// 	// $student_name = $student_name;
		// 	header("url: home");
		// 	die("<script>
		// 			alert('Please fill in the form');
		// 		</script>");
		// }
		// else {
		// 	$stmt->execute();
		// }


		if ($stmt->errorCode() !== '00000') {
			die($stmt->errorInfo()[0]);
		}
	}

	public function removeFromEnrollment($student_id) {
		$stmt = DB::getConnection()->prepare("
			DELETE FROM enrollment
			WHERE student_id = :student_id
		");
		if ($stmt->errorCode()) {
			die($stmt->errorInfo()[0]);
		}
		$stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
		$stmt->execute();
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

	public function delete($student_id) {
		$stmt = DB::getConnection()->prepare("
			DELETE FROM students
			WHERE id = :student_id
		");
		$stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->errorCode() !== '00000') {
			die($stmt->errorInfo()[0]);
		}
	}


}