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
//		$activeStatus = true;

		return $activeStatus;
	}

	function activate() {
		file_put_contents($this->config["incron"]["path"] . "/bittorrent.start", "1");

		sleep(2);
	}

	function deactivate() {
		file_put_contents($this->config["incron"]["path"] . "/bittorrent.stop", "1");

		sleep(10);
	}

	function getTorrents() {
		$auth = $this->config["bittorrent"]["user"] . ":" . $this->config["bittorrent"]["user"];

		$result = "ID     Done       Have  ETA           Up    Down  Ratio  Status       Name
1   100%   320.5 MB  Done         0.0     0.0    0.0  Seeding      xxx.yyy
2   100%   396.2 MB  Done         0.0     0.0    0.0  Idle         lorem.ipsum
3   100%   298.1 MB  Done         0.0     0.0    0.0  Idle         doloris.est
4   100%   257.3 MB  Done         0.0     0.0    0.0  Idle         zzz.txt
5     0%       None  Unknown      0.0     0.0   None  Idle         titi[huhu]
6     0%   65.54 kB  10 hrs       0.0    44.0    0.0  Downloading  big.file.zip
7     1%    4.89 MB  18 min       0.0  1244.0    0.0  Downloading  big.file.zip
8     7%   24.96 MB  5 min        0.0  1354.0    0.0  Downloading  big.file.zip
9    82%   284.9 MB  45 sec       0.0  1725.0    0.0  Downloading  big.file.zip
Sum:        1.27 GB               0.0     0.0
";

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