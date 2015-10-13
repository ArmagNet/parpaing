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

$actions = array("add" => 0, "update" => 0);
$configurationId = $_REQUEST["idInput"];

if (!$configurationId) {
	$configurationId = md5(time(), false);
}

$configuration = array();
$configuration["id"] = $configurationId;
$configuration["label"] = $_REQUEST["labelInput"];

$configuration["crt"] = "";
$configuration["key"] = "";
$configuration["cacrt"] = "";

if (isset($_FILES) && isset($_FILES["certButton"]) && $_FILES["certButton"]["name"]) {
	$content = file_get_contents($_FILES["certButton"]["tmp_name"]);
	$configuration["crt"] = $content;
}

if (isset($_FILES) && isset($_FILES["keyButton"]) && $_FILES["keyButton"]["name"]) {
	$content = file_get_contents($_FILES["keyButton"]["tmp_name"]);
	$configuration["key"] = $content;
}

if (isset($_FILES) && isset($_FILES["caButton"]) && $_FILES["caButton"]["name"]) {
	$content = file_get_contents($_FILES["caButton"]["tmp_name"]);
	$configuration["cacrt"] = $content;
}

$configuration["json"] = array();
$configuration["active"] = false;

$configuration["json"]["dev"] = $_REQUEST["devInput"];
$configuration["json"]["proto"] = $_REQUEST["protoInput"];

$configuration["json"]["remote_cert_tls"] = $_REQUEST["remoteCertTlsInput"];
$configuration["json"]["remote"] = $_REQUEST["remoteIpInput"] . " " . $_REQUEST["remotePortInput"];
$configuration["json"]["cipher"] = $_REQUEST["cipherInput"];
$configuration["json"]["comp_lzo"] = $_REQUEST["compLzoInput"];

$previousConfiguration = $vpnConfigurationBo->getConfigurationById($configurationId);
if ($previousConfiguration) {
	if (!$configuration["crt"]) $configuration["crt"] = $previousConfiguration["crt"];
	if (!$configuration["key"]) $configuration["key"] = $previousConfiguration["key"];
	if (!$configuration["cacrt"]) $configuration["cacrt"] = $previousConfiguration["cacrt"];

	if (isset($previousConfiguration["active"]) && $previousConfiguration["active"]) {
		$configuration["active"] = true;

		// activate this configuration for change
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

$configurations = $vpnConfigurationBo->getConfigurations();

$configurationMap = array();

foreach($configurations as $configuration) {
	$configurationMap[$configuration["id"]] = array("label" => $configuration["label"], "active" => $configuration["active"], "hasKey" => false);
	if (isset($configuration["key"]) && $configuration["key"]) {
		$configurationMap[$configuration["id"]]["hasKey"] = true;
	}
}

echo json_encode(array("ok" => "ok", "actions" => $actions, "configurations" => $configurationMap));

?>