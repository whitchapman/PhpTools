<?php

//requires $error_log (LogWriter) or $logs (LogManager) to log output

function log_error($text, $type) {
	global $logs;
	global $error_log;
	if (isset($logs)) {
		$logs->log_error($text, $type);
	} else if (isset ($error_log)) {
		$error_log->log_message($text, $type);
	}
}

function exn_to_severity(Exception $exn) {
	if (method_exists($exn, "getSeverity")) {
		return $exn->getSeverity();
	} else {
		return 0;
	}
}

function exn_to_string(Exception $exn) {
	$msg = "Type: ".get_class($exn);
	$msg .= "; Severity:".exn_to_severity($exn);
	$msg .= "; Message: ".$exn->getMessage();
	$msg .= "; File: ".$exn->getFile();
	$msg .= "; Line: ".$exn->getLine();
	return $msg;
}

function error_handler($num, $text, $file, $line, $context=null)
{
	exception_handler(new ErrorException($text, 0, $num, $file, $line));
}

function exception_handler(Exception $exn)
{
	$msg = exn_to_string($exn);

	switch (exn_to_severity($exn)) {
		case 0:
			log_error($msg, "EXN");
			break;
		case E_NOTICE:
		case E_STRICT:
		case E_DEPRECATED:
			log_error($msg, "WRN");
			break;
		default:
			log_error($msg, "ERR");
			//$error_log->close();
			//exit();
	}
}

function check_error()
{
	$error = error_get_last();
	if ($error["type"] > 0) {
		error_handler($error["type"], $error["message"], $error["file"], $error["line"]);
	}
}

error_reporting(E_ALL);
set_error_handler("error_handler");
set_exception_handler("exception_handler");
register_shutdown_function("check_error");

?>
