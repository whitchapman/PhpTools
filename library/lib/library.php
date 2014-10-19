<?php

//this file sets up the entire library for use, including all log files.
//in cases where only part of the library is needed, inlcude individual files in the app's global.php.
//constants are required to use this file; however, they are always passed into the other library files as variables.

//-----------------------------------------------------------------
//includes - required constants indicated

//requires LIB_DIR
if (!defined("LIB_DIR")) {
	define("LIB_DIR", dirname(__FILE__)."/");
}
require_once(LIB_DIR."log_writer.php");

//requires LOG_DIR
$error_log = new LogWriter(LOG_DIR."error.log");
require_once(LIB_DIR."error.php");

//log manager is created unless specifically discluded
if (!defined("LIBRARY_DISCLUDE_LOGS") || (LIBRARY_DISCLUDE_CURL === false)) {
	//requires DEBUG flag
	require_once(LIB_DIR."log_manager.php");
	$logs = new LogManager($error_log, DEBUG, LOG_DIR);
}

//requires LIBRARY_INCLUDE_CURL
if (defined("LIBRARY_INCLUDE_CURL") && (LIBRARY_INCLUDE_CURL === true)) {
	require_once(LIB_DIR."curl.php");
}

//requires LIBRARY_INCLUDE_DB and database config: DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME
if (defined("LIBRARY_INCLUDE_DB") && (LIBRARY_INCLUDE_DB === true)) {
	require_once(LIB_DIR."pdo.php");
	$db_factory = new DatabaseFactory(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
}

//requires LIBRARY_INCLUDE_EMAIL and  EMAIL_FROM
if (defined("LIBRARY_INCLUDE_EMAIL") && (LIBRARY_INCLUDE_EMAIL === true)) {
	require_once(LIB_DIR."email.php");
	$email_wrapper = new EmailWrapper(EMAIL_FROM);
	function send_email($to, $subject, $msg, $from=NULL) {
		global $email_wrapper;
		return $email_wrapper->send($to, $subject, $msg, $from);
	}
} else {
	function send_email($to, $subject, $msg, $from=NULL) {
		return true;
	}
}

//requires LIBRARY_INCLUDE_XML
if (defined("LIBRARY_INCLUDE_XML") && (LIBRARY_INCLUDE_XML === true)) {
	require_once(LIB_DIR."xml.php");
}

require_once(LIB_DIR."file_system.php");

//-----------------------------------------------------------------
