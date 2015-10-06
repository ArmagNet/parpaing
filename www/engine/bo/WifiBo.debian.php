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
		// TODO
		WifiBo::sendCommand("iwinfo radio0 assoclist");
	}

	function getInfo() {
		$info = WifiBo::sendCommand("cat /etc/hostapd/hostapd.conf");

		$lines = explode("\n", $info);
		$infos = array();

		$infos["disabled"] = WifiBo::sendCommand("/etc/init.d/hostapd status");
		$infos["encryption"] = "none";

		if (strpos($infos["disabled"], "not running") === false) {
			$infos["disabled"] = 0;
		}
		else {
			$infos["disabled"] = 1;
		}

		foreach($lines as $line) {
			if (strlen($line) > 0 && substr($line, 0, 1) == "#") continue;

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
				case "wpa":
					$infos["wpa"] = $value;
					break;
				case "wpa_pairwise":
					$infos["wpa_pairwise"] = $value;
					break;
				case "rsn_pairwise":
					$infos["rsn_pairwise"] = $value;
					break;
			}
		}

		if (isset($infos["key"]) && $infos["key"] && isset($infos["wpa"])) {
			if ($infos["wpa"] == 1) {
				$infos["encryption"] = "psk";
			}
			else if ($infos["wpa"] == 2) {
				$infos["encryption"] = "psk2";
				if ($infos["wpa_pairwise"] == "TKIP") {
					$infos["encryption"] = "psk-mixed";
				}
			}
		}

		return $infos;
	}

	function activate() {
		file_put_contents($this->config["incron"]["path"] . "/hostapd.activate", "1");
		sleep(10);

	}

	function deactivate() {
		file_put_contents($this->config["incron"]["path"] . "/hostapd.deactivate", "1");
		sleep(2);
	}

	function setConfiguration($configuration) {
		$updated = false;

		$oldConfiguration = $this->getInfo();

		$hostapd = "";

		if (isset($configuration["ssid"])) {
			$hostapd .= "ssid=" . $configuration["ssid"] . "\n";
			$updated = true;
		}
		else {
			$hostapd .= "ssid=" . $oldConfiguration["ssid"] . "\n";
		}

		if (isset($configuration["channel"])) {
			$hostapd .= "channel=" . $configuration["channel"] . "\n";
			$updated = true;
		}
		else {
			$hostapd .= "channel=" . $oldConfiguration["channel"] . "\n";
		}

		if ($configuration["encryption"] == "psk") {
			$hostapd .= "
wpa_passphrase=" . $configuration["key"]  . "
wpa=1
wpa_key_mgmt=WPA-PSK
wpa_pairwise=TKIP";
		}
		else if ($configuration["encryption"] == "psk2") {
			$hostapd .= "
wpa_passphrase=" . $configuration["key"]  . "
wpa=2
wpa_key_mgmt=WPA-PSK
wpa_pairwise=CCMP
rsn_pairwise=CCMP";
		}
		else if ($configuration["encryption"] == "psk-mixed") {
			$hostapd .= "
wpa_passphrase=" . $configuration["key"]  . "
wpa=2
wpa_key_mgmt=WPA-PSK
wpa_pairwise=TKIP
rsn_pairwise=CCMP";
		}

		$hostapd .= "
interface=wlan0
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
			file_put_contents($this->config["incron"]["path"] . "/hostapd.conf", $hostapd);
			sleep(10);
		}

		return $updated;
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>