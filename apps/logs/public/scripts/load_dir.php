{
<?php

require_once("../global.php");

//-----------------------------------------------------------------

$dir_key = $_POST["dir_key"];
$log_dir = $input_dirs[$dir_key];

//verifying that the directory was configured
$valid_dir = in_array($log_dir, $input_dirs, true);
if (!$valid_dir) {
	exit();
}

if ((strlen($log_dir) == 0) || (!file_exists($log_dir))) {

	print "\"valid\":false,";
	print "\"msg\":\"invalid log_dir [{$log_dir}]\"";

} else {


	print "\"files\":[";
	$files = FileSystemWrapper::dir_to_files_with_ext($log_dir, ".log");
	$first = true;
	foreach ($files as $file) {
		if ($first) {
			$first = false;
		} else {
			print ", ";
		}
		print "\"".$file."\"";
	}
	print "],";

	print "\"valid\":true,";
	print "\"msg\":\"".$dir_key."\"";

}

?>
}
