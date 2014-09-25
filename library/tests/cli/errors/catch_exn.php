<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "attempting to catch an exception".PHP_EOL;
try {
	throw new Exception("an exception has occurred");
}
catch(Exception $exn) {
	print "caught the exception: ".exn_to_string($exn).PHP_EOL;
}

print "DONE".PHP_EOL;
