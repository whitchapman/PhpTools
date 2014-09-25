<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "connecting to database...".PHP_EOL;
$db = $db_factory->get_wrapper();

print "inserting row into table...".PHP_EOL;

$sql = "INSERT INTO tests (created_at, test_description) VALUES (now(), :description)";
$description = "Newest test dated ".$time_stamp;

$stmt = $db->prepare($sql);
$stmt->bind_params(array(":description"=>$description));
$stmt->execute();

print "#rows: ".$stmt->row_count().PHP_EOL;

print "Insert id: ".$db->last_insert_id().PHP_EOL;

print "DONE".PHP_EOL;
