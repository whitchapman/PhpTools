<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "connecting to database...".PHP_EOL;
$db = $db_factory->get_wrapper();

print "querying table...".PHP_EOL;

$helper = new QueryHelper();
$helper->add_update_clause("test_description = :description");
$helper->add_string_param(":description", "New Test!");
$helper->add_update_clause("test_status = :status1");
$helper->add_string_param(":status1", 2);

$helper->add_where_clause("test_status = :status2");
$helper->add_int_param(":status2", 1);
$helper->add_where_clause("test_key = :key");
$helper->add_int_param(":key", 4);

//$sql = "SELECT test_key, created_at, updated_at, test_description, test_status FROM test";
$sql = "UPDATE test";
$sql .= $helper->get_update_sql();
$sql .= $helper->get_where_sql();

print "SQL=|".$sql."|".PHP_EOL;

$stmt = $db->prepare($sql);
$helper->apply_params($stmt);
$result = $stmt->execute();
print "RESULT=".$result.PHP_EOL;

print "DONE".PHP_EOL;
