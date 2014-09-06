<?php

class LogFormatter {

	public static function msg_to_line($text , $type) {
		$timestamp = date("Ymd H:i:s");
		return $timestamp."|".$type."|".$text.PHP_EOL;
	}
}

class LogWriter {
	private $path;
	private $logging_blocks;
	private $fd;

	//appends to an existing log
	public function __construct($path, $date_prepend=false, $logging_blocks=true) {
		if ($date_prepend) {
			$this->path = dirname($path)."/".date("Ymd")."_".basename($path);
		} else {
			$this->path = $path;
		}
		$this->logging_blocks = $logging_blocks;
	}

	public function log_message($text, $type="MSG") {
		if (!isset($this->fd)) {
			$this->fd = fopen($this->path, "a+");
			if ($this->logging_blocks) {
				//start of logging block
				$this->log_message("", "EOB");
				$start_line = LogFormatter::msg_to_line("START - ".$_SERVER["PHP_SELF"], "MSG");
				fwrite($this->fd, $start_line);
			}
		}

		$line = LogFormatter::msg_to_line($text, $type);
		fwrite($this->fd, $line);
	}

	public function close() {
		if ($this->logging_blocks) {
			//end of logging block
			$this->log_message("DONE");
			$this->log_message("", "EOB");
		}

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
