<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "attempting to divide by zero".PHP_EOL;
$x = 3 / 0;

print "DONE".PHP_EOL;
