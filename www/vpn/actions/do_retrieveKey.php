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

require_once("config/config.php");
require_once("engine/utils/SessionUtils.php");
require_once('engine/bo/VpnConfigurationBo.php');

if (!isset($_SERVER["HTTP_REFERER"])) exit();
if (!SessionUtils::isConnected($_SESSION)) exit();

function getOvpn($configuration) {

	$ovpn = "";

	$ovpn .= "dev " . $configuration["dev"] . "\n";
	$ovpn .= "proto " . $configuration["proto"] . "\n";
	$ovpn .= "log /my/log/path/openvpn.log\n";
	$ovpn .= "verb 3\n";
	$ovpn .= "ca \"" . $configuration["id"] . ".cert\"\n";
	$ovpn .= "cert \"" . $configuration["label"] . ".crt\"\n";
	$ovpn .= "key \"" . $configuration["label"] . ".key\"\n";
	$ovpn .= "client 1\n";
	$ovpn .= "remote-cert-tls " . $configuration["remote_cert_tls"] . "\n";
	$ovpn .= "remote " . $configuration["remote"] . "\n";
	$ovpn .= "cipher " . $configuration["cipher"] . "\n";
	$ovpn .= "comp-lzo " . $configuration["lzo"] . "\n";

	return $ovpn;
}

$vpnConfigurationBo = VpnConfigurationBo::newInstance($config);
$vpnId = $_REQUEST["vpnId"];

$configuration = $vpnConfigurationBo->getConfigurationById($vpnId);

$content = null;

switch($_REQUEST["type"]) {
	case "key":
		$content = $configuration["key"];
		$filename = $configuration["label"] . ".key";
		break;
	case "cert":
		$content = $configuration["crt"];
		$filename = $configuration["label"] . ".cert";
		break;
	case "cacert":
		$content = $configuration["cacrt"];
		$filename = $configuration["id"] . ".cert";
		break;
	case "dh":
		$content = $configuration["dh"];
		$filename = $configuration["id"] . ".pem";
		break;
	case "ovpn":
		$content = getOvpn($configuration);
		$filename = $configuration["id"] . ".ovpn";
		break;
}

if (!$content) {
//	echo "null";
	header("Location: index.php");
	exit();
}

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"$filename\"");

echo $content;

?>