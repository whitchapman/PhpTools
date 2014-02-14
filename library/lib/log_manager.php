<?php

class LogManager {
	private $dir;
	private $debug;
	private $error_log;

	private $debug_log;
	private $curl_log;
	private $db_log;
	private $email_log;
	private $xml_log;

	//appends to an existing log
	public function __construct($error_log, $debug, $dir) {
		$this->debug = $debug;
		$this->error_log = $error_log;

		$this->debug_log = new LogWriter($dir."debug.log");
		$this->curl_log = new LogWriter($dir."curl.log");
		$this->db_log = new LogWriter($dir."db.log");
		$this->email_log = new LogWriter($dir."email.log");
		$this->xml_log = new LogWriter($dir."xml.log");

		//if debug is true, then display page access information even if there are no errors
		if ($this->debug === true) {
			$this->debug_log->log_message("DEBUG ON");
		}
	}

	public function log_debug($text, $type="MSG") {
		if ($this->debug === true) {
			$this->debug_log->log_message($text, $type);
		}
	}

	public function log_error($text, $type="MSG") {
		$this->error_log->log_message($text, $type);
		$this->log_debug($text, $type);
	}

	private function is_error($type) {
		switch ($type) {
			case "ERR":
			case "WRN":
				return true;
				break;
			default:
				return false;
				break;
		}
	}

	public function log_curl($text, $type="MSG") {
		$this->curl_log->log_message($text, $type);
		if ($this->is_error($type)) {
			$this->error_log->log_message($text, $type);
		}
		$this->log_debug($text, $type);
	}

	public function log_db($text, $type="MSG") {
		$this->db_log->log_message($text, $type);
		if ($this->is_error($type)) {
			$this->error_log->log_message($text, $type);
		}
		$this->log_debug($text, $type);
	}

	public function log_email($text, $type="MSG") {
		$this->email_log->log_message($text, $type);
		if ($this->is_error($type)) {
			$this->error_log->log_message($text, $type);
		}
		$this->log_debug($text, $type);
	}

	public function log_xml($text, $type="MSG") {
		$this->xml_log->log_message($text, $type);
		if ($this->is_error($type)) {
			$this->error_log->log_message($text, $type);
		}
		$this->log_debug($text, $type);
	}

/*
	public function close() {
		$this->debug_log->close();
		$this->error_log->close();
		$this->curl_log->close();
		$this->db_log->close();
		$this->email_log->close();
	}

	public function __destruct() {
		$this->close();
	}
*/
}

?>
