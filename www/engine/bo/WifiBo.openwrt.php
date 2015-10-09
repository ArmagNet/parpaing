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
		WifiBo::sendCommand("iwinfo radio0 assoclist");
	}

	function getInfo() {
		if (!$this->config || !$this->config["wifi"]["isdummy"]) {
			$info = WifiBo::sendCommand("cat /etc/config/wireless");
		}
		else {
			$info = "
config wifi-device 'radio0'
	option type 'mac80211'
	option hwmode '11ng'
	option path 'platform/ar933x_wmac'
	option htmode 'HT20'
	option disabled '0'
	option channel 'auto'
	option txpower '18'
	option country 'US'

config wifi-iface
	option device 'radio0'
	option mode 'ap'
	option network 'lan'
	option encryption 'psk-mixed'
	option disabled '0'
	option ssid 'LaBriqueArmagnet'
	option key 'archange'
		";
		}

		$lines = explode("\n", $info);
		$infos = array();

		$infos["disabled"] = 0;

		foreach($lines as $line) {
			$explLine = explode(" ", trim($line), 3);
			if (count($explLine) < 2) {
				continue;
			}
			else if (count($explLine) < 3) {
				$value = "";
			}
			else {
				$value = $explLine[2];
			}

			if (substr($value, 0, 1) == "'") {
				$value = substr($value, 1, strlen($value) - 2);
			}

			switch($explLine[1]) {
				case "wifi-device":
					$wifiDevice = $value;
					$wifiIface = "";
					break;
				case "wifi-iface":
					$wifiDevice = "";
					$wifiIface = $value;
					break;
				case "channel":
					$infos["channel"] = $value;
					break;
				case "ssid":
					$infos["ssid"] = $value;
					break;
				case "key":
					$infos["key"] = $value;
					break;
				case "encryption":
					$infos["encryption"] = $value;
					break;
				case "disabled":
					if ($wifiDevice) {
						$infos["disabled"] = $value;
					}
					break;
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