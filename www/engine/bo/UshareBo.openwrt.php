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

class UshareBo {
	var $config = null;

	function __construct($config) {
		$this->config = $config;
	}

	static function newInstance($config) {
		return new UshareBo($config);
	}

	function isActive() {
		$activeStatus = UshareBo::sendCommand("ps | grep ushare");

		$activeStatus = strpos($activeStatus, "/usr/bin/ushare") !== false;

		return $activeStatus;
	}

	function activate() {
		UshareBo::sendCommand("uci set ushare.@ushare[0].content_directories=" . $this->config["parpaing"]["root_directory"]);
		UshareBo::sendCommand("uci commit transmission");
		
		UshareBo::sendCommand("/etc/init.d/ushare enable");
		UshareBo::sendCommand("/etc/init.d/ushare restart");

		do {
			sleep(1);
		}
		while (!$this->isActive());
	}

	function deactivate() {
		UshareBo::sendCommand("/etc/init.d/ushare stop");
		UshareBo::sendCommand("/etc/init.d/ushare disable");

		do {
			sleep(1);
		}
		while ($this->isActive());
	}

	function getInterface() {
		return trim(UshareBo::sendCommand("uci get ushare.@ushare[0].interface"));
	}

	function setInterface($interface) {
		UshareBo::sendCommand("uci set ushare.@ushare[0].interface=$interface");
		UshareBo::sendCommand("uci commit ushare");
		
		if ($this->isActive()) {
			UshareBo::sendCommand("/etc/init.d/ushare restart");
		}
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>