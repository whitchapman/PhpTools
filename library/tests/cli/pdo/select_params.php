<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "connecting to database...".PHP_EOL;
$db = $db_factory->get_wrapper();

print "querying table...".PHP_EOL;

$sql = "SELECT test_key, created_at, updated_at, test_description FROM tests WHERE test_key IN (:key1, :key2, :key3)";
//$sql = "desc tests";

$stmt = $db->prepare($sql);
//$stmt->bind_params(array(":key1"=>"2", ":key2"=>"4", ":key3"=>"5"));
$stmt->bind_int_value(":key1", 2);
$stmt->bind_int_value(":key2", 3);
$stmt->bind_int_value(":key3", 5);
$stmt->execute();
$rs = $stmt->fetch_all();
print_r($rs);

print "DONE".PHP_EOL;

?>
