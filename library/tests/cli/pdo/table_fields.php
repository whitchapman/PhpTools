<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "connecting to database...".PHP_EOL;
$db = $db_factory->get_wrapper();

print "querying table...".PHP_EOL;

$sql = $db->build_table_column_names_sql("tests", 1);

print "SQL=|".$sql."|".PHP_EOL;

print "DONE".PHP_EOL;
