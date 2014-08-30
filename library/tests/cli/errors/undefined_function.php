<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "if error not shown below, check /var/log/php.log for undefined function call".PHP_EOL;

if (function_exists("undefined_function_call_test")) {
	print "function_exists()=true".PHP_EOL;
} else {
	print "function_exists()=false".PHP_EOL;
}

undefined_function_call_test();

print "DONE".PHP_EOL;

?>
