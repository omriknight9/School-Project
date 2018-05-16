<?php 

class DB {
	private static $conn;
	private function __construct() {}

	public static function getConnection () {
		if (!self::$conn) {			
			// self::$conn = new mysqli('localhost', 'root', '', 'store');
			try {
				self::$conn = new PDO('mysql:dbname=college;host=localhost', 'root', '');
				self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);     
				self::$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false );           
				self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				die($e->getMessage());
			}
			return self::$conn;
		} else {
			return self::$conn;
		}
	}
}