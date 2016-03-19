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

class VpnBo {
	var $config = null;

	function __construct($config) {
		$this->config = $config;
	}

	static function newInstance($config) {
		return new VpnBo($config);
	}

	function setConfiguration($configuration) {
		file_put_contents($this->config["openvpn"]["config"] . "_cacert.crt", $configuration["cacrt"]);
		file_put_contents($this->config["openvpn"]["config"] . "_cert.crt", $configuration["crt"]);
		file_put_contents($this->config["openvpn"]["config"] . "_key.key", $configuration["key"]);

		VpnBo::sendCommand("echo > " . $this->config["openvpn"]["config"]);
		VpnBo::sendCommand("uci set openvpn.myvpn=openvpn");
		VpnBo::sendCommand("uci set openvpn.myvpn.enabled=1");
		VpnBo::sendCommand("uci set openvpn.myvpn.dev=" . $configuration["json"]["dev"]);
		VpnBo::sendCommand("uci set openvpn.myvpn.proto=" . $configuration["json"]["proto"]);
		VpnBo::sendCommand("uci set openvpn.myvpn.log=/tmp/openvpn.log");
		VpnBo::sendCommand("uci set openvpn.myvpn.verb=3");
		VpnBo::sendCommand("uci set openvpn.myvpn.ca=" . $this->config["openvpn"]["config"] . "_cacert.crt");
		VpnBo::sendCommand("uci set openvpn.myvpn.cert=" . $this->config["openvpn"]["config"] . "_cert.crt");
		VpnBo::sendCommand("uci set openvpn.myvpn.key=" . $this->config["openvpn"]["config"] . "_key.key");
		VpnBo::sendCommand("uci set openvpn.myvpn.client=1");
		VpnBo::sendCommand("uci set openvpn.myvpn.remote_cert_tls=" . $configuration["json"]["remote_cert_tls"]);
		VpnBo::sendCommand("uci set openvpn.myvpn.remote='" . $configuration["json"]["remote"] . "'");
		VpnBo::sendCommand("uci set openvpn.myvpn.cipher=" . $configuration["json"]["cipher"]);
		VpnBo::sendCommand("uci set openvpn.myvpn.comp_lzo=" . $configuration["json"]["comp_lzo"]);
		VpnBo::sendCommand("uci set openvpn.myvpn.push='route 192.168.1.0 255.255.255.0'");
		VpnBo::sendCommand("uci commit openvpn");
//		VpnBo::sendCommand("/etc/init.d/openvpn restart");
	}

	function activateForwarding($interface) {
		VpnBo::sendCommand("uci set openvpn.myvpn.push='route 192.168.1.0 255.255.255.0'");

		VpnBo::sendCommand("uci set firewall.@forwarding[0].dest=lan");
		VpnBo::sendCommand("uci set firewall.@forwarding[0].src=$interface");
		VpnBo::sendCommand("uci set firewall.@forwarding[1].dest=$interface");
		VpnBo::sendCommand("uci set firewall.@forwarding[1].src=lan");

		VpnBo::sendCommand("uci commit firewall");
		VpnBo::sendCommand("/etc/init.d/firewall restart");
	}

	function activate($configuration = null) {
		if ($configuration) {
			$this->setConfiguration($configuration);
		}

		$this->activateForwarding("vpn");

		VpnBo::sendCommand("/etc/init.d/openvpn enable");
		VpnBo::sendCommand("/etc/init.d/openvpn restart");

		sleep(10);
	}

	function deactivate() {
		$this->activateForwarding("wan");

		VpnBo::sendCommand("/etc/init.d/openvpn stop");
		VpnBo::sendCommand("/etc/init.d/openvpn disable");
	}


	function isActive() {
		$activeStatus = VpnBo::sendCommand("ps | grep openvpn");

		$activeStatus = strpos($activeStatus, "/usr/sbin/openvpn") !== false;

		return $activeStatus;
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>