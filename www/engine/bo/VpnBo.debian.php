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

 		$openvpn = "";
 		$openvpn .= "dev " . $configuration["json"]["dev"] . "\n";
 		$openvpn .= "proto " . $configuration["json"]["proto"] . "\n";
 		$openvpn .= "log /var/log/openvpn.log\n";
 		$openvpn .= "verb 3\n";
 		$openvpn .= "ca /etc/openvpn/openvpn_cacert.crt\n";
 		$openvpn .= "cert /etc/openvpn/openvpn_cert.crt\n";
 		$openvpn .= "key /etc/openvpn/openvpn_key.key\n";
 		$openvpn .= "client 1\n";
 		$openvpn .= "remote-cert-tls " . $configuration["json"]["remote_cert_tls"] . "\n";
 		$openvpn .= "remote " . $configuration["json"]["remote"] . "\n";
 		$openvpn .= "cipher " . $configuration["json"]["cipher"] . "\n";
 		$openvpn .= "comp-lzo " . $configuration["json"]["comp_lzo"] . "\n";
 		$openvpn .= "push route 192.168.1.0 255.255.255.0\n";

 		file_put_contents($this->config["openvpn"]["config"] . ".conf", $openvpn);
	}

	function isActive() {
		$activeStatus = VpnBo::sendCommand("/etc/init.d/openvpn status");

		$activeStatus = strpos($activeStatus, "not running") === false;

		return $activeStatus;
	}

	function activate($configuration = null) {
		if ($configuration) {
			$this->setConfiguration($configuration);
			file_put_contents($this->config["incron"]["path"] . "/openvpn.restart", "1");
		}
		else {
			file_put_contents($this->config["incron"]["path"] . "/openvpn.activate", "1");
		}

		sleep(5);
	}

	function deactivate() {
		file_put_contents($this->config["incron"]["path"] . "/openvpn.deactivate", "1");

		sleep(1);
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>