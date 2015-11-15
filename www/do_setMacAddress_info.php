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
require_once("config/config.php");
require_once("engine/utils/SessionUtils.php");
require_once 'engine/bo/NetworkBo.'.$config["parpaing"]["dialect"].'.php';

if (!SessionUtils::isConnected($_SESSION)) {
	exit();
}

$networkBo = NetworkBo::newInstance($config);

$macAddress = $_REQUEST["macAddress"];
$macs = $networkBo->getMacs();
if (isset($macs[$macAddress])) {
	$mac = $macs[$macAddress];
}
else {
	$mac = array("type" => "", "label" => "");
}

if (isset($_REQUEST["label"])) {
	$mac["label"] = $_REQUEST["label"];
}

if (isset($_REQUEST["type"])) {
	$mac["type"] = $_REQUEST["type"];
}

$macs[$macAddress] = $mac;

$networkBo->saveMacs($macs);

echo json_encode(array("ok" => "ok"));

?>