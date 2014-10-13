<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "connecting to database...".PHP_EOL;
$db = $db_factory->get_wrapper();

print "querying table...".PHP_EOL;

$helper = new QueryHelper();
$helper->add_where_clause("test_status = :status");
$helper->add_int_param(":status", 1);
$helper->add_where_clause("test_key > :key");
$helper->add_int_param(":key", 2);

$sql = "SELECT test_key, created_at, updated_at, test_description, test_status FROM test";
$sql .= $helper->get_where_sql();

print "SQL=|".$sql."|".PHP_EOL;

$stmt = $db->prepare($sql);
$helper->apply_params($stmt);
$stmt->execute();
$rs = $stmt->fetch_all();
print_r($rs);

print "DONE".PHP_EOL;
