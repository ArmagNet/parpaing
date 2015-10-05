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

		$infos["disabled"] = WifiBo::sendCommand("/etc/init.d/hostapd status");
		$infos["encryption"] = "none";

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
					$infos["wpa_key_mgmt"] = $value;
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
//		WifiBo::sendCommand("uci set wireless.@wifi-device[0].disabled=0; uci commit wireless; wifi");
		WifiBo::sendCommand("/etc/init.d/hostapd start");
	}

	function deactivate() {
//		WifiBo::sendCommand("uci set wireless.@wifi-device[0].disabled=1; uci commit wireless; wifi");
		WifiBo::sendCommand("/etc/init.d/hostapd stop");
	}

	function setConfiguration($configuration) {
		$updated = false;

		$oldConfiguration = $this->getInfo();

		$hostapd = "";

		if (isset($configuration["ssid"])) {
			$hostapd += "ssid=" . $configuration["ssid"] . "\n";
			$updated = true;
		}
		else {
			$hostapd += "ssid=" . $oldConfiguration["ssid"] . "\n";
		}

		if (isset($configuration["channel"])) {
			$hostapd += "channel=" . $configuration["channel"] . "\n";
			$updated = true;
		}
		else {
			$hostapd += "channel=" . $oldConfiguration["channel"] . "\n";
		}

		// TODO
// 		if (isset($configuration["ssid"])) {
// 			WifiBo::sendCommand("uci set wireless.@wifi-iface[0].key='" . $configuration["key"] . "';");
// 			$updated = true;
// 		}
// 		if (isset($configuration["encryption"])) {
// 			WifiBo::sendCommand("uci set wireless.@wifi-iface[0].encryption='" . $configuration["encryption"] . "';");
// 			$updated = true;
// 		}

		$hostapd += "interface=wlan0
bridge=br0
driver=rtl871xdrv
country_code=FR
ctrl_interface=/var/run/hostapd
hw_mode=g
beacon_int=100
macaddr_acl=0
wmm_enabled=1
ieee80211n=1
ht_capab=[SHORT-GI-20][SHORT-GI-40][HT40+]
";

		if ($updated) {
			file_put_contents("/etc/hostapd/hostapd.conf", $hostapd);
			WifiBo::sendCommand("/etc/init.d/hostapd restart");
		}

		return $updated;
	}

// 	function setSsid($ssid) {
// 		WifiBo::sendCommand("uci set wireless.@wifi-iface[0].ssid='$ssid'; uci commit wireless; wifi");
// 	}

// 	function setChannel($channel) {
// 		WifiBo::sendCommand("uci set wireless.@wifi-device[0].channel='$channel'; uci commit wireless; wifi");
// 	}

// 	function setKey($key) {
// 		WifiBo::sendCommand("uci set wireless.@wifi-iface[0].key='$key'; uci commit wireless; wifi");
// 	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>