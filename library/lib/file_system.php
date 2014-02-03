<?php

//-----------------------------------------------------------------
//file system functions

class FileSystemWrapper {

	public static function dir_to_files($dir) {
		$files = array();
		if ($handle = opendir($dir)) {
			while (($file = readdir($handle)) !== false) {
				if ($file != "." && $file != "..") {
					$files[] = $file;
				}
			}
			closedir($handle);
		}
		return $files;
	}

	public static function dir_to_files_with_ext($dir, $ext) {
		$files = array();
		$len = strlen($ext);
		if ($handle = opendir($dir)) {
			while (($file = readdir($handle)) !== false) {
				if ($file != "." && $file != "..") {
					if (substr($file, -1 * $len) == $ext) {
						$files[] = $file;
					}
				}
			}
			closedir($handle);
		}
		return $files;
	}
}

//-----------------------------------------------------------------

?>
