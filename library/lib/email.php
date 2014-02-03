<?php

//requires $logs (LogManager) to log output
//if not using localhost, requires email params setup

require_once("Mail.php");

function log_email($text, $type="MSG") {
	global $logs;
	if (isset($logs)) {
		$logs->log_email($text, $type);
	}
}

//-----------------------------------------------------------------

class EmailWrapper {
	private $from;
	private $params;
	private $smtp;

	public function __construct($from) {
		$this->from = $from;
	  $this->params = array(
  	  "auth" => false,
    	"debug" => false,
    	"host" => "localhost"
  	);

	  //for zoho, username must be an existing mailbox
	  //$params["host"] = "ssl://smtp.zoho.com";
  	//$params["port"] = "465";
  	//$params["auth"] = true;
  	//$params["username"] = "deals@dealble.com";
  	//$params["password"] = "";
	}

	public function send($to, $subject, $msg) {

		if (!isset($this->smtp)) {
			$this->smtp = Mail::factory("smtp", $this->params);
		}

	  $headers = array(
    	"MIME-Version" => "1.0",
    	"Content-type" => "text/html; charset=iso-8859-1;",
    	"From" => $this->from,
    	"To" => $to,
    	"Subject" => $subject
  	);

  	$result = $this->smtp->send($to, $headers, $msg);

  	if (PEAR::isError($result)) {
    	log_email("PEAR send smtp [".$to."] error: ".$result->getMessage(), "ERR");
    	return false;
	  } else {
			log_email("PEAR send smtp [".$to."]: SUCCESS");
	  	return true;
		}
	}
}

?>
