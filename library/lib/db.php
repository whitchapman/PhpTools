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
			$text = "mysqli connect error #".$this->conn->connect_errno.": ".$this->conn->connect_error;
			log_db($text, "ERR");
			return;
		}

		if (!$this->conn->set_charset("utf8")) {
			$text = "Warning: error changing db charset from ".$this->conn->character_set_name();
			$text .= " to utf8 #".$this->conn->errno.": ".$this->conn->error;
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
			$text = "query error #".$this->conn->errno.": ".$this->conn->error;
			log_db($text, "ERR");
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
			$text = "prepare error #".$this->conn->errno.": ".$this->conn->error;
			log_db($text, "ERR");
		}
		return $result;
	}

	public function stmt_insert($stmt) {
		if ($stmt->execute()) {
			return $this->conn->insert_id;
		} else {
			$text = "insert error #".$this->conn->errno.": ".$this->conn->error;
			log_db($text, "ERR");
			return false;
		}
	}

	public function stmt_select($stmt) {
		if ($stmt->execute()) {
			return $stmt->result_metadata();
		} else {
			$text = "select error #".$this->conn->errno.": ".$this->conn->error;
			log_db($text, "ERR");
			return false;
		}
	}

	public function stmt_bind_assoc ($stmt, &$out) {
		$out = array();
		if ($result = $stmt->result_metadata()) {
			$fields = array();
			$fields[0] = $stmt;

			while ($field = $result->fetch_field()) {
				$fields[] = &$out[$field->name];
			}			
			$result->free();

			call_user_func_array(mysqli_stmt_bind_result, $fields);
			return true;
		} else {
			$text = "stmt_bind_assoc: no stmt result";
			log_db($text, "WRN");
			return false;
		}
	}

	public function stmt_select_record_set($stmt, &$num_records) {
		if ($this->stmt_select($stmt)) {
			if ($this->stmt_bind_assoc($stmt, $row)) {
				$keys = array_keys($row);

				$num_records = 0;
				$rs = array();
				while ($stmt->fetch()) {
					$num_records++;
					$copy = array();
					foreach ($keys as $key) {
						$copy[$key] = $row[$key];
					}
					$rs[] = $copy;
				}
		
				return $rs;
			}
		}

		return false;
	}

	public function stmt_select_csv($stmt, &$num_records) {
		if ($this->stmt_select($stmt)) {
			if ($this->stmt_bind_assoc($stmt, $row)) {
				$keys = array_keys($row);

				$str = "";
				foreach ($keys as $key) {
					if (empty($str)) {
						$str .= $key;
					} else {
						$str .= ",".$key;
					}
				}
				$str .= PHP_EOL;

				$num_records = 0;
				while ($stmt->fetch()) {
					$num_records++;
					$line = "";
					foreach ($row as $value) {
						if (empty($line)) {
							$line .= $value;
						} else {
							$line .= ",".$value;
						}
					}
					$str .= $line.PHP_EOL;
				}
		
				return $str;
			}
		}

		return false;
	}

	public function stmt_select_html_table($stmt, &$num_records) {
		if ($this->stmt_select($stmt)) {
			if ($this->stmt_bind_assoc($stmt, $row)) {
				$keys = array_keys($row);

				$html = '<table border="1" cellspacing="0" cellpadding="8">'.PHP_EOL;
				$html .= '<tr>'.PHP_EOL;
				foreach ($keys as $key) {
					$html .= '<th>'.$key.'</th>'.PHP_EOL;
				}
				$html .= '</tr>'.PHP_EOL;

				$num_records = 0;
				while ($stmt->fetch()) {
					$num_records++;
					$html .= '<tr>'.PHP_EOL;
					foreach ($row as $value) {
						$html .= '<td>'.$value.'</td>'.PHP_EOL;
					}
					$html .= '</tr>'.PHP_EOL;
				}
		
				return $html."</table>".PHP_EOL;
			}
		}

		return false;
	}
}

//-----------------------------------------------------------------

?>
