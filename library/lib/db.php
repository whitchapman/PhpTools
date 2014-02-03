<?php

//requires $logs (LogManager) to log output

function log_db($text, $type="MSG") {
	global $logs;
	if (isset($logs)) {
		$logs->log_db($text, $type);
	}
}

//-----------------------------------------------------------------
//database factory - singleton enforcer

class DatabaseFactory {
	private $server;
	private $user;
	private $password;
	private $database;

	private static $db_wrapper;

	public function __construct($server, $user, $password, $database) {
		$this->server = $server;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}

	public function get_wrapper() {
		if (!isset(self::$db_wrapper)) {
			self::$db_wrapper = new DatabaseWrapper($this->server, $this->user, $this->password, $this->database);
		}
		return self::$db_wrapper;
	}
}

//-----------------------------------------------------------------
//database wrapper

class DatabaseWrapper {
	private $conn;

	public function __construct($server, $user, $password, $database) {
		$this->conn = new mysqli($server, $user, $password, $database);

		if ($this->conn->connect_errno) {
			log_db("Connect Error: #{$this->conn->connect_errno} {$this->conn->connect_error}", "ERR");
			return;
		}

		if (!$this->conn->set_charset("utf8")) {
			$text = "Warning: Error changing character from ".$this->conn->character_set_name()." to utf8:".$this->conn->error;
			log_db($text, "WRN");
		}

		log_db("Connection open");
	}

	public function __destruct() {
		$this->conn->close();
		log_db("Connection closed");
	}

	public function is_connected() {
		if (isset($this->conn)) {
			$this->conn->ping();
		} else {
			return false;
		}
	}

	//TODO: make more robust by checking connection and making multiple query attempts
	public function query($sql) {
		log_db($sql, "SQL");
		$result = $this->conn->query($sql);
		if ($this->conn->errno) {
			log_db("Query Error: #{$this->conn->errno} {$this->conn->error}", "ERR");
		}
		return $result;
	}

	public function build_fields_sql($table, $start_field_pos) {
		$sql = "desc ".$table;
		$result = $this->query($sql);

		$fields = array();
		while ($row = $result->fetch_assoc()) {
			$fields[] = $row["Field"];
		}

		$result->close();

		$fields_sql = "";
		$length = count($fields);
		for ($i = $start_field_pos-1; $i < $length; $i++) {
			$fields_sql .= ", ".$fields[$i];
		}
		return $fields_sql;
	}

	public function escape($data) {
		if (is_null($data)) {
			return null;
		}
		return mysql_real_escape_string($data);
	}

	public function prepare($sql) {
		log_db($sql, "SQL");
		$result = $this->conn->prepare($sql);
		if ($result === false) {
			$text = "Warning: mysqli prepare failed #{$this->conn->errno} {$this->conn->error}";
			log_db($text, "WRN");
		}
		return $result;
	}

	public function insert($stmt) {
		if ($stmt->execute()) {
			return $stmt->insert_id;
		} else {
			$text = "Warning: mysqli statement insert failed #{$this->conn->errno} {$this->conn->error}";
			log_db($text, "WRN");
			return false;
		}
	}

	public function select($stmt) {
		if ($stmt->execute()) {
			return true; //$stmt->get_result();
		} else {
			$text = "Warning: mysqli statement select failed #{$this->conn->errno} {$this->conn->error}";
			log_db($text, "WRN");
			return false;
		}
	}
}

//-----------------------------------------------------------------

?>
