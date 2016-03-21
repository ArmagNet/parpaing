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
require_once("engine/bo/UserBo.php");
require_once("engine/utils/SessionUtils.php");

$data = array();

$userBo = UserBo::newInstance($config);
$user = $userBo->getUser();

$password = $_REQUEST["password"];

//if ($user["password"] == $password) {
if ($userBo->checkRootPassword($password)) {
	if ($_REQUEST["newPassword"]) {
		if ($_REQUEST["newPassword"] != $_REQUEST["confirmNewPassword"]) {
			$data["status"] = "renew_password";
			$data["message"] = "notSameNewPassword";
		}
		else {
			$userBo->setPassword($password, $_REQUEST["newPassword"]);
			$data["status"] = "ok";
			SessionUtils::login($_SESSION);
		}
	}
	else if ($password == $config["parpaing"]["default_password"]) {
			$data["status"] = "renew_password";
			$data["message"] = "defaultPassword";
	}
	else {
		$data["status"] = "ok";
		SessionUtils::login($_SESSION);
	}
}
else {
	$data["status"] = "ko";
	$data["message"] = "badPassword";
}
echo json_encode($data);
?>