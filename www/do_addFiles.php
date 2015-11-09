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

//print_r($_FILES);

if (!isset($_FILES["files"])) {
	echo json_encode(array("ko" => "no_file"));
	exit();
}

$files = array();
foreach($_FILES["files"]["tmp_name"] as $index => $tmp_name) {
	$files[$index]["tmp_name"] = $tmp_name;
	$files[$index]["name"] = $_FILES["files"]["name"][$index];
	$files[$index]["error"] = $_FILES["files"]["error"][$index];
	$files[$index]["size"] = $_FILES["files"]["size"][$index];
	$files[$index]["type"] = $_FILES["files"]["type"][$index];
}

foreach($files as $file) {
	$filepath = $fullpath . $file["name"];
	move_uploaded_file($file["tmp_name"], $filepath);
}

/*

if ($file["error"] != UPLOAD_ERR_OK) {
	$data = array("ko" => "ko");
	switch($file["error"]) {
		case UPLOAD_ERR_INI_SIZE :
			$data["message"] = "error_media_sizeError";
			$data["maxSize"] = file_upload_max_size();
			break;
		default:
			$data["message"] = "error_media_defaultError";
	}

	echo json_encode($data);
	exit();
}

*/
$data = array();
$data["ok"] = "ok";

echo json_encode($data);

?>