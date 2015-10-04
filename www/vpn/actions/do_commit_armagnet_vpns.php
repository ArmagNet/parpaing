<?php
session_start();

$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'config/config.php';
require_once 'vpn/api_client.php';
require_once 'engine/bo/VpnBo.'.$config["parpaing"]["dialect"].'.php';
require_once 'engine/bo/VpnConfigurationBo.php';

$vpnConfigurationBo = VpnConfigurationBo::newInstance($config);
$vpnBo = VpnBo::newInstance($config);

$configurations = json_decode($_SESSION["VPN_CONFIGURATIONS"], true);
$configurationIds = $_POST["configuration_ids"];
$actions = array("add" => 0, "update" => 0);

foreach($configurationIds as $configurationId) {
	error_log("Configuration id : " . $configurationId);

	foreach($configurations as $index => $configuration) {
		if ($configuration["id"] == $configurationId) {

			$previousConfiguration = $vpnConfigurationBo->getConfigurationById($configurationId);
			if ($previousConfiguration) {
				if ($previousConfiguration["active"]) {
					$configuration["active"] = true;

					// TODO re-activate this configuration for change
					$vpnBo->activate($configuration);
					// TODO check the differences, if any, re-activate
				}

				$vpnConfigurationBo->updateConfiguration($configuration);
				$actions["update"] = $actions["update"] + 1;
			}
			else {
				$vpnConfigurationBo->addConfiguration($configuration);
				$actions["add"] = $actions["add"] + 1;
			}
		}
	}
}

$configurations = $vpnConfigurationBo->getConfigurations();

$configurationMap = array();

foreach($configurations as $configuration) {
	$configurationMap[$configuration["id"]] = array("label" => $configuration["label"], "active" => $configuration["active"]);
}

echo json_encode(array("ok" => "ok", "actions" => $actions, "configurations" => $configurationMap));

?>