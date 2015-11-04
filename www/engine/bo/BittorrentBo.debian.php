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

class BittorrentBo {
	var $config = null;

	function __construct($config) {
		$this->config = $config;
	}

	static function newInstance($config) {
		return new BittorrentBo($config);
	}

	function isActive() {
		$activeStatus = BittorrentBo::sendCommand("/etc/init.d/transmission-daemon status");

		$activeStatus = strpos($activeStatus, "not running") === false;

		return $activeStatus;
	}

	function activate() {
		file_put_contents($this->config["incron"]["path"] . "/bittorrent.start", "1");

		sleep(2);
	}

	function deactivate() {
		file_put_contents($this->config["incron"]["path"] . "/bittorrent.stop", "1");

		do {
			sleep(1);
		}
		while ($this->isActive());
	}

	function addTorrent($torrent) {
		$auth = $this->config["bittorrent"]["user"] . ":" . $this->config["bittorrent"]["user"];
		$result = BittorrentBo::sendCommand("transmission-remote --auth $auth --add \"".$torrent."\"");

		return $result;
	}

	function getTorrents() {
		$auth = $this->config["bittorrent"]["user"] . ":" . $this->config["bittorrent"]["user"];

		$result = BittorrentBo::sendCommand("transmission-remote --auth $auth -l");
		$result = preg_split("/\n/", $result);

		$torrents = array();

		foreach($result as $index => $line) {
			$data = preg_split("/[\s]+/", trim($line));

			if (!is_numeric($data[0])) continue;

// 			print_r($data);

			$torrent = array();

			//	ID     Done       Have  ETA           Up    Down  Ratio  Status       Name
			$state = "id";
			for($index = 0; $index < count($data); $index++) {
				$torrent[$state] = $data[$index];

				switch($state) {
					case "id":
						$state = "done";
						break;
					case "done":
						$state = "have";
						break;
					case "have":
						if ($torrent["have"] != "None") {
							$index++;
							$unit = $data[$index];
							$torrent["have_unit"] = $unit;
						}
						$state = "eta";
						break;
					case "eta":
						if ($torrent["eta"] != "Done" && $torrent["eta"] != "Unknown") {
							$index++;
							$unit = $data[$index];
							$torrent["eta_unit"] = $unit;
						}
						$state = "up";
						break;
					case "up":
						$state = "down";
						break;
					case "down":
						$state = "ratio";
						break;
					case "ratio":
						$state = "status";
						break;
					case "status":
						$state = "name";
						break;
				}
			}

			$torrent["up"] *= 1024;
			$torrent["down"] *= 1024;

			$torrents[] = $torrent;
		}
		return $torrents;
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>