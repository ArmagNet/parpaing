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

$fromVersion = $_REQUEST["from_version"];
$toVersion = $_REQUEST["to_version"];

$jsonVersionsUrl = $config["parpaing"]["base_upgrade_url"] . "versions.json";
$jsonVersions = json_decode(file_get_contents($jsonVersionsUrl), true);

//print_r($jsonVersions);
//echo "\n";

$upgraders = array();

$numberOfActions = 0;

foreach($jsonVersions["versions"] as $version) {
	if ($version["version"] <= $fromVersion) continue;
	if ($version["version"] > $toVersion) continue;

//	echo "Found " . $version["version"] . "\n";

	$jsonUpgraderUrl = $config["parpaing"]["base_upgrade_url"] . $version["version"] . "/upgrader.json?t=" . time();
//	echo "jsonUpgraderUrl : $jsonUpgraderUrl \n";

	$jsonUpgraderContent = file_get_contents($jsonUpgraderUrl);
//	echo "$jsonUpgraderContent \n";
	
	$jsonUpgrader = json_decode($jsonUpgraderContent, true);

// 	print_r($jsonUpgrader);
// 	echo "\n";
	
	$upgraders[] = $jsonUpgrader;
	$numberOfActions += count($jsonUpgrader["actions"]);
}

$_SESSION["upgraders"] = json_encode($upgraders);
$_SESSION["upgrader_index"] = 0;
$_SESSION["action_index"] = 0;
$_SESSION["number_of_actions"] = $numberOfActions;
$_SESSION["number_of_done_actions"] = 0;
$_SESSION["number_of_upgraders"] = count($upgraders);

echo json_encode(array("ok" => "ok", "number_of_actions" => $_SESSION["number_of_actions"], "number_of_upgraders" => $_SESSION["number_of_upgraders"]));

//print_r($_SESSION);

session_write_close();
?>