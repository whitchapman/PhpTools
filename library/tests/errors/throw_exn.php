<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "attempting to throw an uncaught exception".PHP_EOL;
throw new Exception("an exception has occurred");

print "DONE".PHP_EOL;

?>
