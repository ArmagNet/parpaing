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

class NetworkBo {
	var $config = null;

	function __construct($config) {
		$this->config = $config;
	}

	static function newInstance($config) {
		return new NetworkBo($config);
	}

	function scan($network, $fromCache = false) {
		$filepath = $this->config["incron"]["path"] . "/$network.nmap";

//		error_log("Scan $network");


		if ($fromCache && !file_exists($filepath)) {
			return array();
		}
		else if (!$fromCache) {
			@unlink($filepath);

			file_put_contents($this->config["incron"]["path"] . "/nmap.su.ip", $network);

			while(!file_exists($filepath) || strpos(file_get_contents($filepath), "done") === false) {
//				error_log("Sleep $network");

				sleep(1);
			}
		}


		$content = file_get_contents($filepath);
//		if (strpos($content, "") === false)

// 		$cmd = "nmap -sU --script nbstat.nse -p137 $network/24";

// 		echo "$cmd\n";

// 		$result = NetworkBo::sendCommand($cmd);

// 		echo "# $result #\n";

		$re = "/^Nmap scan report for (.*)$/m";
		preg_match_all($re, $content, $matches);

		//		print_r($matches);

		$ips = array();

		foreach($matches[1] as $ip_value) {
			$ip = array();
			$ip["ip"] = $ip_value;

			$index = strpos($content, $ip_value);
			$end = strpos($content, "Nmap scan report", $index);

			if ($end !== false) {
				$ip_part = substr($content, $index, $end - $index);
			}
			else {
				$ip_part = substr($content, $index);
			}

			$re = "/^MAC Address: (.*) \\((.*)\\)$/m";
			preg_match_all($re, $ip_part, $macMatches);

			$ip["mac_address"] = $macMatches[1][0];
			$ip["card_name"] = $macMatches[2][0];

			$re = "/NetBIOS name: (.*?),/m";
			preg_match_all($re, $ip_part, $netbiosMatches);

			if (count($netbiosMatches[1])) {
				$ip["netbios"] = $netbiosMatches[1][0];
			}

			if (true) {
				$ip["type"] = "desktop";
				$ip["label"] = "Desktop";
			}

//			print_r($macMatches);

//			echo $ip_part;

//			print_r($ip);

			$ips[] = $ip;

//			echo "\n";
		}

//		$result = array($content);

		return $ips;
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>