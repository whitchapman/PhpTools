<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "attempting to tigger an error".PHP_EOL;
trigger_error("an error has occurred");

print "DONE".PHP_EOL;
