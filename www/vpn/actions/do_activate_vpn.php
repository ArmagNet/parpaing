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
require_once 'engine/bo/VpnBo.'.$config["parpaing"]["dialect"].'.php';
require_once 'engine/bo/VpnConfigurationBo.php';

$vpnConfigurationBo = VpnConfigurationBo::newInstance($config);
$vpnBo = VpnBo::newInstance($config);
$vpnId = $_REQUEST["vpn_id"];

$configuration = $vpnConfigurationBo->getConfigurationById($vpnId);

if (!$configuration["active"]) {
	// ACTIVATE
	$vpnBo->deactivate();
	$vpnConfigurationBo->activate($configuration);
	$vpnBo->activate($configuration);
}

$configurations = $vpnConfigurationBo->getConfigurations();
$configurationMap = array();

foreach($configurations as $configuration) {
	$configurationMap[$configuration["id"]] = array("label" => $configuration["label"], "active" => $configuration["active"]);
}

$activeStatus = $vpnBo->isActive();

echo json_encode(array("ok" => "ok", "isActive" => $activeStatus, "actions" => array(), "configurations" => $configurationMap));

?>