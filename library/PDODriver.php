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

	// get primary key of table
	private function __getPrimaryKey($table)
	{
		$colums = array();
		$sql = "SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'";
		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			if ($stmt->execute()) {
				$colums = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			$stmt->closeCursor();
		}
		return $colums['Column_name'];
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

	// get all data by one field
	function getDataByName($table, $field, $value)
	{
		$sql  = "SELECT * FROM {$table} WHERE {$field} = :{$field}";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			$stmt->bindParam(":{$field}", $value, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$this->__data = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			$stmt->closeCursor();
		}
		return $this->__data;
	}

	// get one data by primary key
	public function getDataById($table, $where)
	{
		$prKey = $this->__getPrimaryKey($table);
		$sql  = "SELECT * FROM {$table} WHERE {$prKey} = :{$prKey}";
		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			$stmt->bindParam(":{$prKey}", $where, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$this->__data = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			$stmt->closeCursor();
		}
		return $this->__data;
	}

	// get lastest inserted data
	function getLastInsertedData($table)
	{
		$primaryKey = $this->__getPrimaryKey($table);
		$sql  = "SELECT * FROM {$table} WHERE {$primaryKey} = LAST_INSERT_ID();";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			if ($stmt->execute()) {
				$this->__data = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			$stmt->closeCursor();
		}
		return $this->__data;
	}

	// insert one record to database
	public function insert($data, $table)
	{
		$filedKey = '';
		$filedPram = '';

		foreach ($data as $key => $value) {
			$filedKey .= ($filedKey == '') ? $key : ',' . $key;
			$filedPram .= ($filedPram == '') ? ':' . $key : ',' . ':' . $key;
		}
		$sql = "INSERT INTO {$table}({$filedKey}) VALUES({$filedPram})";
		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			foreach ($data as $k => &$v) {
				$stmt->bindParam(':' . $k, $v, PDO::PARAM_STR);
			}
			if ($stmt->execute()) {
				$this->__flag = TRUE;
			}
			$stmt->closeCursor();
		}
		return $this->__flag;
	}

	// update one record in database
	public function update($table, $data, $where)
	{
		$flideValue = '';
		foreach ($data as $key => $val) {
			$flideValue .= ($flideValue == '') ? "{$key} = :{$key}" : ", {$key} = :{$key}";
		}
		$prKey = $this->__getPrimaryKey($table);
		$sql  = "UPDATE {$table} SET {$flideValue} WHERE {$prKey} = :{$prKey}";
		$stmt = $this->conn->prepare($sql);

		if ($stmt) {
			// bind param to data fields
			foreach ($data as $k => &$v) {
				$stmt->bindParam(":{$k}", $v, PDO::PARAM_STR);
			}
			// bind param to primary key
			$stmt->bindParam(":{$prKey}", $where, PDO::PARAM_STR);
			if ($stmt->execute()) {
				$this->__flag = TRUE;
			}
			$stmt->closeCursor();
		}
		return $this->__flag;
	}

	// count record in table
	public function countAllData($table)
	{
		$sql  = "SELECT COUNT(*) AS 'total_record' FROM {$table}";
		$stmt = $this->conn->prepare($sql);
		if ($stmt) {
			if ($stmt->execute()) {
				$this->__data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			$stmt->closeCursor();
		}
		return $this->__data[0]['total_record'];
	}

	// disconnect database
	function __destruct()
	{
		$this->conn = null;
	}
}
