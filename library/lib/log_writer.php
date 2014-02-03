<?php

class LogFormatter {

	public static function msg_to_line($text , $type) {
		$timestamp = date("Ymd H:i:s");
		return $timestamp."|".$type."|".$text.PHP_EOL;
	}
}

class LogWriter {
	private $path;
	private $fd;

	//appends to an existing log
	public function __construct($path) {
		$this->path = $path;
	}

	public function log_message($text, $type="MSG") {
		if (!isset($this->fd)) {
			$this->fd = fopen($this->path, "a+");
			$start_line = LogFormatter::msg_to_line("START - ".$_SERVER["PHP_SELF"], "MSG");
			fwrite($this->fd, $start_line);
		}

		$line = LogFormatter::msg_to_line($text, $type);
		fwrite($this->fd, $line);
	}

	public function close() {
		//end of logging block
		$this->log_message("DONE");
		$this->log_message("", "EOB");

		fclose($this->fd);
		$this->fd = NULL;
	}

	public function __destruct() {
		if (isset($this->fd)) {
			$this->close();
		}
	}
}

?>
