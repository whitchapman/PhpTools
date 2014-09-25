<?php

require_once("../global.php");

//-----------------------------------------------------------------

$dir_key = $_POST["dir_key"];
$log_file = $_POST["log_file"];
$log_path = $input_dirs[$dir_key].$log_file;

//verifying that the path's directory was configured
$log_dir = dirname($log_path)."/";
$valid_dir = in_array($log_dir, $input_dirs, true);
if (!$valid_dir) {
	exit();
}

print "Archiving ".$log_path."...<br>";

$archive_success = false;
if (is_readable($log_path)) {
	$lines = file($log_path);

	$archive_path = $log_path.".archive";

	print "Appending ".$log_path." to ".$archive_path."...<br>";

	//need to give create file permission to apache user to copy files
	//copy($path, $archive_path)
	//or die("Error: <font color=red>could not archive file</font>");

	if (is_writable($archive_path)) {
		if ($fp = fopen($archive_path, 'a')) {

			foreach ($lines as $line) {
				fwrite($fp, $line);
			}

			fclose($fp);
			print "Log file archived<br>";
			$archive_success = true;
		} else {
			print "Error: <font color=red>could not open archive file</font>";
		}
	} else {
		print "Error: <font color=red>archive file not writable</font>";
	}

} else {
	print "Error: <font color=red>log file not found</font>";
}

if ($archive_success) {
	print "Clearing ".$log_path."...<br>";

	if (is_writable($log_path)) {
		if ($fp = fopen($log_path, 'w')) {
			fwrite($fp, "");
			fclose($fp);
			print "Log file cleared";
		} else {
			print "Error: <font color=red>could not open log file</font>";
		}
	} else {
		print "Error: <font color=red>log file not writable</font>";
	}
}
