<?php
session_start();

$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'config/config.php';
require_once 'engine/bo/VpnConfigurationBo.php';

$vpnConfigurationBo = VpnConfigurationBo::newInstance($config);
$vpnId = $_REQUEST["vpn_id"];

$configuration = $vpnConfigurationBo->getConfigurationById($vpnId);

if ($configuration) {
	if ($configuration["active"]) {
		// TODO de-activate VPN
	}
	$vpnConfigurationBo->deleteConfiguration($configuration);
}

$configurations = $vpnConfigurationBo->getConfigurations();
$configurationMap = array();

foreach($configurations as $configuration) {
	$configurationMap[$configuration["id"]] = array("label" => $configuration["label"], "active" => $configuration["active"]);
}

echo json_encode(array("ok" => "ok", "actions" => $actions, "configurations" => $configurationMap));

?>