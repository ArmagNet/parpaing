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

if (!isset($_SERVER["HTTP_REFERER"])) exit();

$zipPath = 'upgrade/upgrade.zip';

$zip = new ZipArchive;
$result = $zip->open($zipPath);
if ($result === TRUE) {
	$index = $zip->locateName("parpaing-master/www/version.json");
// 	$stats = $zip->statIndex($index);
	$json = $zip->getFromIndex($index);
	$version = json_decode($json, true);

	$zip->close();

	echo json_encode(array("ok" => "ok", "version" => $version));

	exit();
}

echo json_encode(array("ko" => "ko"));
?>