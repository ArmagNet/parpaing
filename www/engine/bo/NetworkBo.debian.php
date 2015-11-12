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

		if ($fromCache && !file_exists($filepath)) {
			return array();
		}
		else if (!$fromCache) {
			@unlink($filepath);

			file_put_contents($this->config["incron"]["path"] . "/nmap.su.ip", $network);

			while(!file_exists($filepath) || strpos(file_get_contents($filepath), "done") === false) {
				sleep(1);
			}
		}

		$content = file_get_contents($filepath);
//		if (strpos($content, "") === false)

// 		$cmd = "nmap -sU --script nbstat.nse -p137 $network/24";

// 		echo "$cmd\n";

// 		$result = NetworkBo::sendCommand($cmd);

// 		echo "# $result #\n";

		$result = $content;

		return $result;
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>