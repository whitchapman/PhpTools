<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "connecting to database...".PHP_EOL;
$db = $db_factory->get_wrapper();

print "querying table...".PHP_EOL;

$sql = "SELECT test_key, created_at, updated_at, test_description FROM tests";
if ($stmt = $db->prepare($sql)) {

	if ($csv = $db->stmt_select_csv($stmt, $num_records)) {
		print "num_records=".$num_records.PHP_EOL;
		print $csv;
	}

}

print "DONE".PHP_EOL;

?>
