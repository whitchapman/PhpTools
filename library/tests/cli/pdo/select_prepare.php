<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "connecting to database...".PHP_EOL;
$db = $db_factory->get_wrapper();

print "querying table...".PHP_EOL;

$sql = "SELECT test_key, created_at, updated_at, test_description FROM tests";
//$sql = "desc tests";

$stmt = $db->prepare($sql);
$stmt->execute();
$rs = $stmt->fetch_all();
print_r($rs);

print "DONE".PHP_EOL;

?>
