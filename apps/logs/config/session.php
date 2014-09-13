<?php

//-----------------------------------------------------------------
//session

//session_start();

//-----------------------------------------------------------------
//config

//INSTALL: create a config file (based on the default) in this directory
require_once("config.php");

//-----------------------------------------------------------------
//settings that are consistent across all environments

//define("", "");

//-----------------------------------------------------------------
//library

//uncomment these to include functionality
//define("LIBRARY_INCLUDE_CURL", true);
//define("LIBRARY_INCLUDE_DB", true);
//define("LIBRARY_INCLUDE_EMAIL", true);

//require_once(LIB_DIR."library.php");

//requires LIB_DIR and LOG_DIR
require_once(LIB_DIR."log_writer.php");
$error_log = new LogWriter(LOG_DIR."error.log");
require_once(LIB_DIR."error.php");

//requires DEBUG flag
require_once(LIB_DIR."log_manager.php");
$logs = new LogManager($error_log, DEBUG, LOG_DIR);

require_once(LIB_DIR."file_system.php");

//-----------------------------------------------------------------

?>
