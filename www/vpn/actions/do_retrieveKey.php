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

	$ovpn .= "dev " . $configuration["json"]["dev"] . "\n";
	$ovpn .= "proto " . $configuration["json"]["proto"] . "\n";
//	$ovpn .= "log /my/log/path/openvpn.log\n";
	$ovpn .= "verb 4\n";
	if (!isset($configuration["cacrt"]) || !$configuration["cacrt"]) {
		$ovpn .= "ca \"" . $configuration["id"] . ".cert\"\n";
	}
	if (!isset($configuration["crt"]) || !$configuration["crt"]) {
		$ovpn .= "cert \"" . $configuration["label"] . ".cert\"\n";
	}
	if (!isset($configuration["key"]) || !$configuration["key"]) {
		$ovpn .= "key \"" . $configuration["label"] . ".key\"\n";
	}
	$ovpn .= "client 1\n";
	$ovpn .= "remote-cert-tls " . $configuration["json"]["remote_cert_tls"] . "\n";
	$ovpn .= "remote " . $configuration["json"]["remote"] . "\n";
	$ovpn .= "cipher " . $configuration["json"]["cipher"] . "\n";
	$ovpn .= "comp-lzo " . $configuration["json"]["lzo"] . "\n";
	$ovpn .= "resolv-retry infinite\n";
	$ovpn .= "nobind\n";
	$ovpn .= "persist-key\n";
	$ovpn .= "persist-tun\n";
	$ovpn .= "\n";
	$ovpn .= "dhcp-option DNS 80.67.169.12\n";
	$ovpn .= "\n";
	$ovpn .= "# Change default routes\n";
	$ovpn .= "redirect-gateway def1\n";
	$ovpn .= "\n";
	$ovpn .= "#tun-ipv6\n";
	$ovpn .= "#route-ipv6 2000::/3\n";

	if (isset($configuration["cacrt"]) && $configuration["cacrt"]) {
		$ovpn .= "\n<ca>\n";
		$ovpn .= $configuration["cacrt"];
		$ovpn .= "\n</ca>\n";
	}

	if (isset($configuration["crt"]) && $configuration["crt"]) {
		$ovpn .= "\n<cert>\n";
		$ovpn .= $configuration["crt"];
		$ovpn .= "\n</cert>\n";
	}

	if (isset($configuration["key"]) && $configuration["key"]) {
		$ovpn .= "\n<key>\n";
		$ovpn .= $configuration["key"];
		$ovpn .= "\n</key>\n";
	}

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