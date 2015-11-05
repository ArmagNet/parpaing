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

	function isActive() {
		$activeStatus = WifiBo::sendCommand("/etc/init.d/hostapd status");

		$activeStatus = strpos($activeStatus, "not running") === false;

		return $activeStatus;
	}

	function getInfo() {
		$info = WifiBo::sendCommand("cat /etc/hostapd/hostapd.conf");

		$lines = explode("\n", $info);
		$infos = array();

		$infos["disabled"] = $this->isActive() ? 0 : 1;
		$infos["encryption"] = "none";

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

		do {
			sleep(1);
		}
		while (!$this->isActive());
	}

	function deactivate() {
		file_put_contents($this->config["incron"]["path"] . "/hostapd.deactivate", "1");

		do {
			sleep(1);
		}
		while ($this->isActive());
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