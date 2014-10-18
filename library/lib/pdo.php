<?php

//requires $logs (LogManager) to log output

function log_db($text, $type="MSG") {
	global $logs;
	if (isset($logs)) {
		$logs->log_db($text, $type);
	}
}

//-----------------------------------------------------------------
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

	public function close_wrapper() {
		if (isset(self::$db_wrapper)) {
			self::$db_wrapper->close();
			self::$db_wrapper = NULL;
		}
	}
}

//-----------------------------------------------------------------
//-----------------------------------------------------------------

class StatementWrapper {
	private $stmt;
	private $params;
	private $bound_array;
	private $bound_value;

	public function __construct($stmt) {
		$this->stmt = $stmt;
		$this->params = array();
		$this->bound_array = false;
		$this->bound_individual = false;
	}

	public function fetch() {
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function fetch_all() {
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function row_count() {
		return $this->stmt->rowCount();
	}

	private function validate_param($name, $value) {
		if (!(isset($name)) || (strlen($name) == 0)) {
			throw new Exception("db statment: attempting to set param with invalid name");
			return false;
		}

		if (isset($this->params[$name])) {
			throw new Exception("db statment: attempting to set param named ".$name." more than once");
			return false;
		}

		$this->params[$name] = $value;
		return true;
	}

	//------------------------------------------------

	private function bind_value($name, $value, $datatype) {
		if ($this->bound_array) {
			throw new Exception("db statment: attempting to bind individual param after already started binding params array");
		}

		if (!$this->validate_param($name, $value)) {
			return false;
		}

		$return = $this->stmt->bindValue($name, $value, $datatype);
		$this->bound_value = true;
		return $return;
	}

	public function bind_bool_value($name, $value) {
		return $this->bind_value($name, $value, PDO::PARAM_BOOL);
	}

	public function bind_int_value($name, $value) {
		return $this->bind_value($name, $value, PDO::PARAM_INT);
	}

	public function bind_null_value($name) {
		return $this->bind_value($name, NULL, PDO::PARAM_NULL);
	}

	public function bind_string_value($name, $value) {
		return $this->bind_value($name, $value, PDO::PARAM_STR);
	}

	//------------------------------------------------

	public function bind_params($params) {
		if ($this->bound_value) {
			throw new Exception("db statment: attempting to bind params after already started binding individually");
		}

		foreach($params as $name => $value) {
			if (!$this->validate_param($name, $value)) {
				return false;
			}

			$this->params[$name] = $value;
			$this->bound_array = true;
		}

		return true;
	}

	//------------------------------------------------

	public function execute() {
		foreach($this->params as $name => $value) {
			if (isset($value)) {
				log_db("param[".$name."] = |".$value."|", "SQL");
			} else {
				log_db("param[".$name."] = |NULL|", "SQL");
			}
		}

		if ($this->bound_array) {
			return $this->stmt->execute($this->params);
		} else {
			return $this->stmt->execute();
		}
	}
}

//-----------------------------------------------------------------
//-----------------------------------------------------------------

class DatabaseWrapper {
	private $conn;

	public function __construct($server, $user, $password, $database) {
		$this->conn = new PDO("mysql:host=".$server.";dbname=".$database.";charset=utf8", $user, $password);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		log_db("Connection opened");
	}

	public function __destruct() {
		if (isset($this->conn)) {
			$this->conn = NULL;
			log_db("Connection destroyed");
		}
	}

	public function close() {
		if (isset($this->conn)) {
			$this->conn = NULL;
			log_db("Connection closed");
		}
	}

	public function last_insert_id($name=NULL) {
		return $this->conn->lastInsertId($name);
	}	

	public function query($sql) {
		log_db($sql, "SQL");
		$stmt = $this->conn->query($sql);
		log_db("Rows affected: ".$stmt->rowCount());
		return new StatementWrapper($stmt);
	}

	public function exec($sql) {
		log_db($sql, "SQL");
		$num_rows = $this->conn->exec($sql);
		log_db("Rows affected: ".$num_rows);
		return $num_rows;
	}

	public function prepare($sql) {
		log_db($sql, "SQL");
		$stmt = $this->conn->prepare($sql);
		return new StatementWrapper($stmt);
	}

	public function build_table_column_names_sql($table, $start_column_number=0) {
		$sql = "desc ".$table;
		$stmt = $this->query($sql);

		$count = 0;
		$fields = array();
		while($row = $stmt->fetch()) {
			if ($count >= $start_column_number) {
				$fields[] = $row["Field"];
			}
			$count++;
		}

		return implode(",", $fields);
	}
}

//-----------------------------------------------------------------
//-----------------------------------------------------------------

class QueryHelper {
	private $update_clauses;
	private $where_clauses;

	private $bool_params;
	private $int_params;
	private $null_params;
	private $string_params;

	public function __construct() {
		$this->update_clauses = array();
		$this->where_clauses = array();
		$this->bool_params = array();
		$this->int_params = array();
		$this->null_params = array();
		$this->string_params = array();
	}

	//------------------------------------------------
	//TODO: add duplicate param checking

	public function and_bool_param($name, $value) {
		$this->bool_params[] = [$name, $value];
	}

	public function add_int_param($name, $value) {
		$this->int_params[] = [$name, $value];
	}

	public function add_null_param($name) {
		$this->null_params[] = [$name, NULL];
	}

	public function add_string_param($name, $value) {
		$this->string_params[] = [$name, $value];
	}

	public function apply_params(&$stmt) {
		foreach ($this->bool_params as $param) {
			$stmt->bind_bool_value($param[0], $param[1]);
		}
		foreach ($this->int_params as $param) {
			$stmt->bind_int_value($param[0], $param[1]);
		}
		foreach ($this->null_params as $param) {
			$stmt->bind_null_value($param[0]);
		}
		foreach ($this->string_params as $param) {
			$stmt->bind_string_value($param[0], $param[1]);
		}
	}

	//------------------------------------------------

	public function add_update_clause($clause) {
		$this->update_clauses[] = $clause;
	}

	public function get_update_sql() {
		$sql = "";

		foreach ($this->update_clauses as $clause) {
			if (strlen($sql) == 0) {
				$sql .= " SET ".$clause;
			} else {
				$sql .= ", ".$clause;
			}
		}

		return $sql;
	}

	//------------------------------------------------

	public function add_where_clause($clause) {
		$this->where_clauses[] = $clause;
	}

	public function get_where_sql() {
		$sql = "";

		foreach ($this->where_clauses as $clause) {
			if (strlen($sql) == 0) {
				$sql .= " WHERE ".$clause;
			} else {
				$sql .= " AND ".$clause;
			}
		}

		return $sql;
	}
}

//-----------------------------------------------------------------
//-----------------------------------------------------------------
