<?php /*
	Copyright 2014 Cédric Levieux, Jérémy Collot, ArmagNet

	This file is part of Parpaing.

    OpenTweetBar is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    OpenTweetBar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with OpenTweetBar.  If not, see <http://www.gnu.org/licenses/>.
*/
session_start();
require_once("config/config.php");
require_once("engine/utils/SessionUtils.php");

if (!isset($_SERVER["HTTP_REFERER"])) exit();

if (true) {
	$upgrade = "upgrade/" . $config["parpaing"]["branch"] . "/www/";

	$files = glob($upgrade . "{*,*/*,*/*/*,*/*/*/*}", GLOB_BRACE);

	foreach($files as $file) {
		$destination = str_replace($upgrade, "", $file);
//		error_log("Handle $file to $destination");
		if (is_dir($file)) {
			if (!is_dir($destination)) {
				mkdir($destination);
			}
		}
		else {
//			error_log("Copy $file to $destination");
			copy($file, $destination);
		}
	}

	echo json_encode(array("ok" => "ok"));

	exit();
}

echo json_encode(array("ko" => "ko"));
?>