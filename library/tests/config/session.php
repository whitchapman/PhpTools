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

require_once(LIB_DIR."library.php");

//-----------------------------------------------------------------
//dated logs

$info_log = new LogWriter(LOG_DIR."info.log", true, false);
$info_log->log_message("file access: ".$_SERVER["PHP_SELF"]);

//-----------------------------------------------------------------
