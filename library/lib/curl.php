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
	private $http_code;

	public function get_http_code() { return $this->http_code; }

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

	public function query($url) {

		curl_setopt($this->ch, CURLOPT_URL, $url);
		$response = curl_exec($this->ch);
		$this->http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

		if ($this->http_code == 200) {
			log_curl("URL=".$url, "QRY");
			//log_curl("HTTP_CODE=".$this->http_code, "AOK");
			return $response;
		}

		if ($this->http_code == 404) {
			log_curl("URL=".$url, "ERR");
			log_curl("HTTP_CODE=".$this->http_code, "ERR");
		} else {
			log_curl("URL=".$url, "WRN");
			log_curl("HTTP_CODE=".$this->http_code, "WRN");
		}

		return false;
	}

	public function query_json($url) {
		if ($response = $this->query($url)) {

			$json = json_decode($response);
			if (isset($json)) {
				return $json;
			}

			log_curl("Failed to parse JSON (below); error #".json_last_error().": ".json_last_error_msg());
			log_curl($response);
		}

		return false;
	}
}

//-----------------------------------------------------------------
