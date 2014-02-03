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

//requires DEBUG flag
require_once(LIB_DIR."log_manager.php");
$logs = new LogManager($error_log, DEBUG, LOG_DIR);

require_once(LIB_DIR."curl.php");

//requires database config: DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME
require_once(LIB_DIR."db.php");
$db_factory = new DatabaseFactory(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

//requires EMAIL_FROM
require_once(LIB_DIR."email.php");
$email_wrapper = new EmailWrapper(EMAIL_FROM);
function send_email($to, $subject, $msg) {
  global $email_wrapper;
  return $email_wrapper->send($to, $subject, $msg);
}

require_once(LIB_DIR."file_system.php");

//-----------------------------------------------------------------

?>
