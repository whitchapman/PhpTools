<?php

//requires $logs (LogManager) to log output

function log_curl($text, $type="MSG") {
	global $logs;
	if (isset($logs)) {
		$logs->log_curl($text, $type);
	}
}

//-----------------------------------------------------------------
//curl

class CurlWrapper {
	private $ch;

	public function __construct() {
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	}

	public function __destruct() {
		curl_close($this->ch);
	}

	public function set_header($header) {
		log_curl("HEADER=".$header);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array($header));
	}

	public function query_url($url) {
		log_curl("URL=".$url);
		curl_setopt($this->ch, CURLOPT_URL, $url);
		$response = curl_exec($this->ch);
		return $response;
	}

	public function get_http_code() {
		$http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		return $http_code;
	}
}

//-----------------------------------------------------------------
