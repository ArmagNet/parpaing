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

function upgradeCopy($upgrader, $action) {
	global $config;

// 	print_r($upgrader);
// 	echo "\n";

	$remoteFileUrl = $config["parpaing"]["base_upgrade_url"] . $upgrader["version"] . "/" . $action["file"];
	
//	if (strpos($remoteFileUrl, ".php") !== false) $remoteFileUrl .= ".static";
	
	$remoteFileContent = file_get_contents($remoteFileUrl);

	$localFileUrl = $action["file"];
	if (substr($localFileUrl, 0, 1) == "/") {
		$localFileUrl = substr($localFileUrl, 1);
	}

// 	echo $localFileUrl . "\n";
// 	echo $remoteFileUrl . "\n";
	
	@unlink($localFileUrl);
	file_put_contents($localFileUrl, $remoteFileContent);
}

function upgradeDelete($action) {
	global $config;

	$localFileUrl = $action["file"];
	if (substr($localFileUrl, 0, 1) == "/") {
		$localFileUrl = substr($localFileUrl, 1);
	}

	@unlink($localFileUrl);
}

function upgradeExecute($action) {
	global $config;
}

//print_r($_SESSION);
//echo "\n";

$numberOfActions = $_SESSION["number_of_actions"];
$numberOfDoneActions = $_SESSION["number_of_done_actions"];

if ($numberOfDoneActions >= $numberOfActions) exit();

$upgraders = json_decode($_SESSION["upgraders"], true);

//printf($upgraders);
//echo "\n";

$actionIndex = $_SESSION["action_index"];
$upgraderIndex = $_SESSION["upgrader_index"];

$upgrader = $upgraders[$upgraderIndex];
$action = $upgrader["actions"][$actionIndex];

//printf($action);
//echo "\n";

switch($action["action"]) {
	case "copy":
		upgradeCopy($upgrader, $action);
		break;
	case "execute":
		upgradeExecute($action);
		break;
	case "delete":
		upgradeDelete($action);
		break;
}


$actionIndex++;

if ($actionIndex >= count($upgrader["actions"])) {
	$actionIndex = 0;
	$upgraderIndex++;
}

$_SESSION["upgrader_index"] = $upgraderIndex;
$_SESSION["action_index"] = $actionIndex;
$_SESSION["number_of_done_actions"] = $numberOfDoneActions + 1;

echo json_encode(array("ok" => "ok", "number_of_done_actions" => $_SESSION["number_of_done_actions"], "number_of_actions" => $_SESSION["number_of_actions"]));
?>