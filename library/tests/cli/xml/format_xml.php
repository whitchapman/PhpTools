<?php

require_once("../global.php");

//-----------------------------------------------------------------

if (count($argv) < 2) {
  print "usage: file".PHP_EOL;
  exit(1);
}
$file = $argv[1];

$xml = simplexml_load_file($file);
if (($formatted = XmlHelper::format_simple_xml($xml)) === false) {
	print "XML parse error".PHP_EOL;
}
print $formatted;

?>
