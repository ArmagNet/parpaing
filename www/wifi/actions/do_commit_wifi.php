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
require_once 'engine/bo/WifiBo.'.$config["parpaing"]["dialect"].'.php';

$wifiBo = WifiBo::newInstance($config);

$configuration = array();
$configuration["ssid"] = $_REQUEST["ssidInput"];
$configuration["channel"] = $_REQUEST["channelInput"];
$configuration["encryption"] = $_REQUEST["encryptionInput"];
$configuration["txpower"] = $_REQUEST["txpowerInput"];

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