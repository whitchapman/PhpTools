<?php

require_once("../global.php");

$time_stamp = date("Ymd H:i:s");
print $time_stamp.PHP_EOL;

print PHP_EOL."1. command line arguments (list):".PHP_EOL;
foreach($argv as $value)
{
	print "  ".$value.PHP_EOL;
}

$n = count($argv);
print PHP_EOL."2. command line arguments [".$n."]:".PHP_EOL;
print "  script=".$argv[0].PHP_EOL;

for ($i=1; $i<$n; $i++)
{
	print "  (".$i.") ".$argv[$i].PHP_EOL;
}
print PHP_EOL;

print "DONE".PHP_EOL;

?>
