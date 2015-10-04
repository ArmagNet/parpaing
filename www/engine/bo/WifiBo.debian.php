<?php
class WifiBo {
	var $config = null;

	function __construct($config) {
		$this->config = $config;
	}

	static function newInstance($config) {
		return new WifiBo($config);
	}

	function getClients() {
		WifiBo::sendCommand("iwinfo radio0 assoclist");
	}

	function getInfo() {
		$info = WifiBo::sendCommand("cat /etc/hostapd/hostapd.conf");

		$lines = explode("\n", $info);
		$infos = array();

		foreach($lines as $line) {
			$explLine = explode("=", trim($line), 2);
			if (count($explLine) < 2) {
				continue;
			}
			else {
				$value = $explLine[1];
			}

			switch($explLine[0]) {
				case "channel":
					$infos["channel"] = $value;
					break;
				case "ssid":
					$infos["ssid"] = $value;
					break;
				case "wpa_passphrase":
					$infos["key"] = $value;
					break;
				case "wpa_key_mgmt":
					$infos["encryption"] = $value;
					break;
// 				case "disabled":
// 					if ($wifiDevice) {
// 						$infos["disabled"] = $value;
// 					}
// 					break;
			}
		}

		return $infos;
	}

	function activate() {
		WifiBo::sendCommand("uci set wireless.@wifi-device[0].disabled=0; uci commit wireless; wifi");
	}

	function deactivate() {
		WifiBo::sendCommand("uci set wireless.@wifi-device[0].disabled=1; uci commit wireless; wifi");
	}

	function setConfiguration($configuration) {
		$updated = false;
		if (isset($configuration["ssid"])) {
			WifiBo::sendCommand("uci set wireless.@wifi-iface[0].ssid='" . $configuration["ssid"] . "';");
			$updated = true;
		}
		if (isset($configuration["ssid"])) {
			WifiBo::sendCommand("uci set wireless.@wifi-device[0].channel='" . $configuration["channel"] . "';");
			$updated = true;
		}
		if (isset($configuration["ssid"])) {
			WifiBo::sendCommand("uci set wireless.@wifi-iface[0].key='" . $configuration["key"] . "';");
			$updated = true;
		}
		if (isset($configuration["encryption"])) {
			WifiBo::sendCommand("uci set wireless.@wifi-iface[0].encryption='" . $configuration["encryption"] . "';");
			$updated = true;
		}

		if ($updated) {
			WifiBo::sendCommand("uci commit wireless; wifi");
		}

		return $updated;
	}

	function setSsid($ssid) {
		WifiBo::sendCommand("uci set wireless.@wifi-iface[0].ssid='$ssid'; uci commit wireless; wifi");
	}

	function setChannel($channel) {
		WifiBo::sendCommand("uci set wireless.@wifi-device[0].channel='$channel'; uci commit wireless; wifi");
	}

	function setKey($key) {
		WifiBo::sendCommand("uci set wireless.@wifi-iface[0].key='$key'; uci commit wireless; wifi");
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>