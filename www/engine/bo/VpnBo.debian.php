<?php
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

	function isActive() {
		$activeStatus = WifiBo::sendCommand("/etc/init.d/openvpn status");

		$activeStatus = strpos($activeStatuts, "not running") !== false;

		return $activeStatus;
	}

	function activate($configuration = null) {
		file_put_contents($this->config["incron"]["path"] . "/openvpn.activate", "1");
	}

	function deactivate() {
		file_put_contents($this->config["incron"]["path"] . "/openvpn.deactivate", "1");
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>