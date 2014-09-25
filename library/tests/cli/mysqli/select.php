<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print "connecting to database...".PHP_EOL;
$db = $db_factory->get_wrapper();

print "querying table...".PHP_EOL;

$sql = "SELECT test_key, created_at, updated_at, test_description FROM tests";
if ($stmt = $db->prepare($sql)) {
	if ($db->stmt_select($stmt)) {
		$stmt->bind_result($key, $created_at, $updated_at, $description);
		while ($stmt->fetch()) {
			print "key=".$key." created_at=".$created_at." updated_at=".$updated_at." description=|".$description."|".PHP_EOL;
		}
	}
}

print "DONE".PHP_EOL;
