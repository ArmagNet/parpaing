<?php /*
	Copyright 2014-2015 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of Parpaing.

    Parpaing is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Parpaing is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Parpaing.  If not, see <http://www.gnu.org/licenses/>.
*/
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
//	error_log("Configuration id : " . $configurationId);

	foreach($configurations as $index => $configuration) {
		if ($configuration["id"] == $configurationId) {

			$previousConfiguration = $vpnConfigurationBo->getConfigurationById($configurationId);
			if ($previousConfiguration) {
				if ($previousConfiguration["active"]) {
					$configuration["active"] = true;

					if (!$configuration["key"]) {
						$configuration["key"] = $previousConfiguration["key"];
					}
					if (!$configuration["key"]) {
						$realVpnHash = substr($configuration["id"], 0, min(64, strlen($configuration["id"])));
						$keyPath = $config["openvpn"]["config"] . "_$realVpnHash" . ".key";

						$configuration["key"] = file_get_contents($keyPath);
					}

					// re-activate this configuration for change
					$vpnBo->activate($configuration);
					// TODO check the differences, if any, re-activate
				}

				$vpnConfigurationBo->updateConfiguration($configuration);
				$actions["update"] = $actions["update"] + 1;
			}
			else {
				if (!$configuration["key"]) {
					$realVpnHash = substr($configuration["id"], 0, min(64, strlen($configuration["id"])));
					$keyPath = $config["openvpn"]["config"] . "_$realVpnHash" . ".key";

					$configuration["key"] = file_get_contents($keyPath);
				}

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