<?php
  include "../global.php";

  //-----------------------------------------------------------------

	$log_path = $_POST["log_path"];
	$reverse = $_POST["reverse"] == "true" ? true : false;

	//verifying that the path's directory was configured
	$log_dir = dirname($log_path)."/";
	$valid_dir = in_array($log_dir, $input_dirs, true);
	if (!$valid_dir) {
		exit();
	}

	print $log_path." contents".($reverse ? " (reversed)" : "").":<br><hr>";

  if (is_readable($log_path)) {

    $source = file($log_path);
    $lines = ($reverse ? array_reverse($source) : $source);

    $buffer = "";
	  foreach ($lines as $line) {

      $arr = explode("|", $line, 3);
      if (count($arr) === 3) {

        if ($arr[1] === "EOB") {
          print $buffer."<br>".PHP_EOL;
          $buffer = "";
        } else {

          if ($arr[1] === "ERR") {
            $color = "red";
          } else if ($arr[1] === "WRN") {
            $color = "yellow";
          } else if ($arr[1] === "SQL") {
            $color = "blue";
          } else {
            $color = "black";
          }
          $text = $arr[0]." ".$arr[1]."> <font color=\"".$color."\">".$arr[2]."</font><br>".PHP_EOL;

          if ($reverse) {
            $buffer = $text.$buffer;
          } else {
            $buffer .= $text;
          }
        }

      } else {
        print $buffer."<br>".PHP_EOL;
        $buffer = "";
        print $line.PHP_EOL;
      }
	  }

    print $buffer."<br>".PHP_EOL;

  } else {
  	print "Error: <font color=red>log file not found</font>";
  }

?>
