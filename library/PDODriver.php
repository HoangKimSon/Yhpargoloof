<?php

/**
 * PDODriver.php
 * 
 * Core function to access database, other model will extent this class
 */

class PDODriver
{
	private $__data = array(); // result after execute sql
	private $__flag = FALSE; // check if result is true or false
	private $__defaultSortField = 'updated_at'; // default sort fiel in sql statement
	function __construct()
	{
		$host = DATABASE_HOST;
		$db_name = DATABASE_NAME;
		$db_user = DATABASE_USER;
		$db_pass = DATABASE_PASS;

		// connect to database
		try {
			$this->conn = new PDO("mysql:host={$host}; dbname={$db_name}", $db_user, $db_pass);
			return $this->conn;
		} catch (PDOException $e) {
			print("ERR: " . $e->getMessage());
		}
	}

	// get all data
	public function getAllData($table, $start, $limit)
	{
		$sql  = "SELECT * FROM {$table} ORDER BY {$this->__defaultSortField} DESC LIMIT ${start}, $limit";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			if ($stmt->execute()) {
				$this->__data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			$stmt->closeCursor();
		}
		return $this->__data;
	}

	// disconnect database
	function __destruct()
	{
		$this->conn = null;
	}
}
