<?php

class Administrator {

	private $signed_in;
	private $administrator_id;
	private $Administrator_name;
	private $Administrator_role;
	private $Administrator_phone;
	private $Administrator_email;
	private $Administrator_password;
	private $Administrator_image_link;
	
	function __construct($Administrator_name, $Administrator_role, $Administrator_phone, $Administrator_email, $Administrator_password, $Administrator_image_link, $Administrator_id = null) {
		$this->Administrator_name = $Administrator_name;
		$this->Administrator_role = $Administrator_role;
		$this->Administrator_phone = $Administrator_phone;
		$this->Administrator_email = $Administrator_email;
		$this->Administrator_password = $Administrator_password;
		$this->Administrator_image_link = $Administrator_image_link;
		if (!is_null($Administrator_id)) {
			$this->Administrator_id = $Administrator_id;
		}
	}

	public function is_signed_in() {
		return $this->signed_in;
	}

	public function save() {
		$stmt = DB::getConnection()->prepare("
			INSERT INTO Administrators (name, role, phone, email, password, image_link)
			VALUES (:Administrator_name, $Administrator_role, :Administrator_phone, :Administrator_email, Administrator_password, :Administrator_image_link)
		");
		if ($stmt->errorCode()) {
			die($stmt->errorInfo()[0]);
		}
		$stmt->bindParam(':Administrator_name', $this->Administrator_name, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_role', $this->Administrator_role, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_phone', $this->Administrator_phone, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_email', $this->Administrator_email, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_password', $this->Administrator_password, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_image_link', $this->Administrator_image_link, PDO::PARAM_STR);
		$stmt->execute();

		$Administrator_id = DB::getConnection()->lastInsertId();
		$this->Administrator_id = $Administrator_id;

		return $Administrator_id;
	}


	public static function getAll() {
		return DB::getConnection()->query("
			SELECT id, name, role, phone, email, password, image_link 
			FROM Administrators
			");
	}

	public static function getOne($id) {
		$stmt = DB::getConnection()->prepare("
			SELECT id, name, role, phone, email, password, image_link
			FROM Administrators 
			WHERE id = :id
			LIMIT 1 
		");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch();

		return $result;
	}

	public function update($Administrator_id, $Administrator_name, $Administrator_phone, $Administrator_email, $Administrator_image_link) {
		$stmt = DB::getConnection()->prepare("
			UPDATE Administrators
			SET name = :Administrator_name, role = Administrator_role, phone = :Administrator_phone, email = :Administrator_email, password = Administrator_password, image_link = :Administrator_image_link
			WHERE id = :Administrator_id
		");
		
		$stmt->bindParam(':Administrator_id', $Administrator_id, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_name', $Administrator_name, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_role', $Administrator_role, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_phone', $Administrator_phone, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_email', $Administrator_email, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_password', $Administrator_password, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_image_link', $Administrator_image_link, PDO::PARAM_STR);
		$stmt->execute();
		// if (empty($Administrator_name)) {
		// 	// $Administrator_name = $Administrator_name;
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

	public function delete($Administrator_id) {
		$stmt = DB::getConnection()->prepare("
			DELETE FROM Administrators
			WHERE id = :Administrator_id
		");
		$stmt->bindParam(':Administrator_id', $student_id, PDO::PARAM_STR);
		$stmt->execute();
		if ($stmt->errorCode() !== '00000') {
			die($stmt->errorInfo()[0]);
		}
	}

	public function connect($Administrator_email, $Administrator_password) {
			$stmt = DB::getConnection()->prepare("
			SELECT id, name, role, phone, email, password, image_link
			FROM Administrators
			WHERE email = :Administrator_email && password = :Administrator_password
			LIMIT 1
		");
		$stmt->bindParam(':Administrator_email', $Administrator_email, PDO::PARAM_STR);
		$stmt->bindParam(':Administrator_password', $Administrator_password, PDO::PARAM_STR);
		$stmt->execute();

		$result = $stmt->fetch();

		if ($stmt->errorCode() !== '00000') {
			die($stmt->errorInfo()[0]);
		}

		$_SESSION = $result;

		return $result;
	}

	// public function getRole($Administrator_role) {
	// 	$stmt = DB::getConnection()->prepare("
	// 		SELECT id, name, role, phone, email, password, image_link
	// 		FROM Administrators
	// 		WHERE role = :Administrator_role
	// 		LIMIT 1
	// 	");
	// 	$stmt->bindParam(':Administrator_role', $Administrator_role, PDO::PARAM_STR);
	// 	$stmt->execute();

	// 	if ($stmt->errorCode() !== '00000') {
	// 		die($stmt->errorInfo()[0]);
	// 	}
	// }

	public function inspect() {
		if (isset($_SESSION['name']) && ($_SESSION['id'])) {
			// $_SESSION['role'] = self::getRole($_SESSION['role']);
			return $_SESSION;
		} else {
			header("Location: /login");
			die(`Didn't connect`);
		}
	}

	public function logout() {
		if (!isset($_SESSION)) {
			session_start();
		}
		session_destroy();
	}


}