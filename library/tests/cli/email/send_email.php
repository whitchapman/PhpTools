<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

$num_args = count($argv);
if ($num_args < 2) {
	print "usage: to_email".PHP_EOL;
	exit(1);
}

$to = $argv[1];

$subject = "test";
$msg = "this is a test email sent using PEAR";
$result = send_email($to, $subject, $msg);
print "sent email to ".$to." result=".$result.PHP_EOL;

print "DONE".PHP_EOL;
