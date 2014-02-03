<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;


print "1. accessing defined constant".PHP_EOL;
define ("DEFINED_CONSTANT", true);
if (defined("DEFINED_CONSTANT")) {
	print "defined=TRUE".PHP_EOL;
} else {
	print "defined=FALSE".PHP_EOL;
}

if (DEFINED_CONSTANT === true) {
	print "constant defined: |".DEFINED_CONSTANT."|".PHP_EOL;
} else {
	print "constant NOT defined: |".DEFINED_CONSTANT."|".PHP_EOL;
}


print PHP_EOL."2. accessing undefined constant".PHP_EOL;

if (defined("UNDEFINED_CONSTANT")) {
	print "defined=TRUE".PHP_EOL;
} else {
	print "defined=FALSE".PHP_EOL;
}

if (UNDEFINED_CONSTANT === true) {
	print "constant defined: |".UNDEFINED_CONSTANT."|".PHP_EOL;
} else {
	print "constant NOT defined: |".UNDEFINED_CONSTANT."|".PHP_EOL;
}


print "DONE".PHP_EOL;

?>
