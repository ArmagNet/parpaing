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

	readfile($fullpath);

}
?>