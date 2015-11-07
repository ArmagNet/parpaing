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
session_start();
require_once("config/config.php");
require_once("engine/utils/SessionUtils.php");

if (!SessionUtils::isConnected($_SESSION)) {
	exit();
}

$path = "/";

if (isset($_REQUEST["path"])) {
	$path = $_REQUEST["path"];

	if (strpos($path, "..") !== false) {
		exit();
	}
}

$fullpath = $config["parpaing"]["root_directory"] . $path;
$filename = substr($fullpath, strrpos($fullpath, "/") + 1);

if (file_exists($fullpath) && is_file($fullpath)) {
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary");
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header('Content-Length: ' . filesize($fullpath));

	set_time_limit(0);

	ignore_user_abort(true);
	$fh = fopen($fullpath, "r");
	$currentPosition = 0;

	if (isset($_REQUEST["streaming"])) {
		$speed = 200 * 1024 / 5;
	}
	else {
		$speed = 20000 * 1024 / 5;
	}
	$gap = 1000000000 / 5;

	$currentSpeed = 0;
	$currentTime = microtime(true);

	while (!feof($fh)) {
		$contents = fread($fh, 8192);
		$currentPosition += strlen($contents);
		$currentSpeed += strlen($contents);

		echo $contents;

		if ($currentSpeed > $speed) {

			$diff = microtime(true) - $currentTime;
			$diff * 1000000000;
			$diff = floor($diff);

			error_log("$currentSpeed vs $speed on " . number_format($diff) . " vs " . number_format($gap) . "");

			if ($diff < $gap) {
				$nanosleep = $gap - $diff;
				time_nanosleep(0, $nanosleep);
			}

			$currentSpeed = 0;
			$currentTime = microtime(true);
		}

		if(connection_status() != CONNECTION_NORMAL)
		{
			fclose($fh);
			error_log("End of connection");
			exit();
		}

		error_log("Connection status : " . connection_status() . " @ " . $currentPosition);
	}

	fclose($fh);

	error_log("End");
	//	readfile($fullpath);
}

exit();

?>