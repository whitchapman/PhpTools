<?php
  require_once("../global.php");
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>LOGS PAGE</title>
    <script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
  </head>
  <body>
    <h1>LOGS PAGE</h1>
   	<div id="dirs">
			<label for="input_dir">Log Directory:</label>
			<select id="input_dir">
<?php

  foreach (array_keys($input_dirs) as $input_dir) {
    print "<option>".$input_dir."</option>".PHP_EOL;
  }

?>
			</select>
    	<input type="button" value="Load Dir" id="load_dir">
			<span id="span1"></span>
		</div>
		<br>
		<br>
		<div id="files">
			<div>Loaded Directory: <span id="loaded_dir"></span></div>
			<br>
			<label for="input_file">File:</label>
			<select id="input_file"></select>
      <div>
      	<input type="button" value="Load Log" id="load_log">
      	<input type="button" value="Archive Log" id="archive_log">
	      <!--<input type="button" value="Clear Log" id="clear_log">-->
      </div>
      <div><input type="checkbox" id="reverse" checked="yes"><label for="reverse">reverse order</label></div>
    </div>
		<br>
		<br>
    <div id="results"></div>
    <script>
$(function() {

	$("#files").hide();

	$("#load_dir").click(function() {

		$("#files").hide();

		var log_dir_arg = $("#input_dir").val();
		if (typeof(log_dir_arg) == "string") {
			log_dir = log_dir_arg;
		} else {
			log_dir = "";
		}
		if (log_dir == "") {
			$("#results").html("<font color=red>Empty dir name</font>");
			return false;
		}

		$("#results").html("Loading Directory...");
		$("#input_file > option").remove();

		$.ajax({
			url: "scripts/load_dir.php",
			data: {
				dir_key: log_dir
			},
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(json) {
				if ((typeof(json.valid) != "boolean") || (typeof(json.msg) != "string")) {
					var html = "Error: Invalid JSON<br>";
					$("#results").html(html + "<pre>" + JSON.stringify(json) + "</pre>");
				} else if (!json.valid) {
					var html = "Error: " + json.msg + "<br>";
					$("#results").html(html + "<pre>" + JSON.stringify(json) + "</pre>");
				} else {
					if (json.files.length == 0) {
						$("#results").html("No Log Files Found in the directory [" + json.msg + "]");
					} else {
						$("#results").html("");
						$("#loaded_dir").html(json.msg);
						$.each(json.files, function (key, value) {
							$("#input_file").append($("<option>").text(value));
						});
						$("#files").show();
					}
				}
			},
			error: function(xhr, status, thrown) {
				$("#results").html("Error returned from ajax: " + thrown);
			},
			complete: function(xhr, status) {
				//alert("The request is complete!");
			}
		});
	});

	$("#load_log").click(function() {

    var log_file_arg = $("#input_file").val();
    if (typeof(log_file_arg) == "string") {
      log_file = log_file_arg;
    } else {
      log_file = "";
    }
    if (log_file == "") {
      $("#results").html("<font color=red>Empty file name</font>");
      return false;
    }

		$("#results").html("Loading File...");

		$.ajax({
			url: "scripts/load_log.php",
			data: {
				dir_key: $("#loaded_dir").html(),
				log_file: log_file,
				reverse: $("#reverse").is(":checked")
			},
			cache: false,
			type: "POST",
			dataType: "html",
			success: function(html) {
				$("#results").html(html);
			},
			error: function(xhr, status, thrown) {
				$("#results").html("Error returned from ajax: " + thrown);
			},
			complete: function(xhr, status) {
				//alert("The request is complete!");
			}
		});
	});

	$("#archive_log").click(function() {

    var log_file_arg = $("#input_file").val();
    if (typeof(log_file_arg) == "string") {
      log_file = log_file_arg;
    } else {
      log_file = "";
    }
    if (log_file == "") {
      $("#results").html("<font color=red>Empty file name</font>");
      return false;
    }

		$("#results").html("Archiving File...");

		$.ajax({
			url: "scripts/archive_log.php",
			data: {
				dir_key: $("#loaded_dir").html(),
				log_file: log_file
			},
			cache: false,
			type: "POST",
			dataType: "html",
			success: function(html) {
				$("#results").html(html);
			},
			error: function(xhr, status, thrown) {
				$("#results").html("Error returned from ajax: " + thrown);
			},
			complete: function(xhr, status) {
				//alert("The request is complete!");
			}
		});
	});


});
    </script>
<?php

  //next line causes error for error handling testing
  //require "this file does not exist";

  //-----------------------------------------------------------------

?>
  </body>
</html>
