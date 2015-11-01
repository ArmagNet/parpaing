<?php
$path = "../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$url = "http://www.bfmtv.com/mediaplayer/live-video/";

readfile($url);

?>