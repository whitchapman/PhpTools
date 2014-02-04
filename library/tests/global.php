<?php

//-----------------------------------------------------------------
//session

//session_start();

//-----------------------------------------------------------------
//config

//INSTALL: create a config file (based on the default) in this directory
require_once("config/config.php");

//-----------------------------------------------------------------
//settings that are consistent across all environments

//define("", "");

//-----------------------------------------------------------------
//library

//uncomment these to include functionality
//define("LIBRARY_INCLUDE_CURL", true);
//define("LIBRARY_INCLUDE_DB", true);
//define("LIBRARY_INCLUDE_EMAIL", true);

require_once(LIB_DIR."library.php");

//-----------------------------------------------------------------

?>
