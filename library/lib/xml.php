<?php

//requires $logs (LogManager) to log output

function log_xml($text, $type="MSG") {
	global $logs;
	if (isset($logs)) {
		$logs->log_xml($text, $type);
	}
}

//-----------------------------------------------------------------
//xml

class XmlHelper {

	public static function format_simple_xml(&$xml) {
		if (!is_object($xml)) {
			return false;
		}
	  $dom = new DOMDocument("1.0");
	  $dom->preserveWhiteSpace = false;
	  $dom->formatOutput = true;
	  $dom->loadXML($xml->asXML());
	  return $dom->saveXML();
	}

}

//-----------------------------------------------------------------

?>
