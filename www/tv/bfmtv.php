<?php
$path = "../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$channel = $_REQUEST["channel"];

switch($channel) {
	case "bfmbusiness":
		$url = "http://bfmbusiness.bfmtv.com/mediaplayer/live-video/";
		break;
	case "bfmtv":
	default:
		$url = "http://www.bfmtv.com/mediaplayer/live-video/";
		break;
}

readfile($url);

?>