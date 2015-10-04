<?php
session_start();

$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'config/config.php';
require_once 'engine/bo/WifiBo.'.$config["parpaing"]["dialect"].'.php';

$wifiBo = WifiBo::newInstance($config);

$configuration = array();
$configuration["ssid"] = $_REQUEST["ssidInput"];
$configuration["channel"] = $_REQUEST["channelInput"];
$configuration["encryption"] = $_REQUEST["encryptionInput"];
if ($configuration["encryption"] == "none") {
	$configuration["key"] = "";
}
else {
	$configuration["key"] = $_REQUEST["passphraseInput"];
}

$wifiBo->setConfiguration($configuration);

$wifiInfos = $wifiBo->getInfo();

echo json_encode(array("ok" => "ok", "wifiInfos" => $wifiInfos));

?>