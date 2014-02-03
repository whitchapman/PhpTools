<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

$arr = array();
$arr[] = "charles";
$arr[] = "emerson";
$arr[] = "winchester";
$arr[] = "the";
$arr[] = "third";

print "print_r:".PHP_EOL;
print_r($arr);

print "var_dump:".PHP_EOL;
var_dump($arr);

$files = array(
	"debug.log",
	"error.log",
	"emails.log",
	"db.log"
);
print "files:".PHP_EOL;
print_r($files);

print "DONE".PHP_EOL;

?>
