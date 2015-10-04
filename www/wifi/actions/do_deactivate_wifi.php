<?php
session_start();

$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'config/config.php';
require_once 'engine/bo/WifiBo.'.$config["parpaing"]["dialect"].'.php';

$wifiBo = WifiBo::newInstance($config);

$wifiBo->deactivate();

$wifiInfos = $wifiBo->getInfo();

echo json_encode(array("ok" => "ok", "wifiInfos" => $wifiInfos));

?>