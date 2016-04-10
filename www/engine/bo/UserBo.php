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

class UserBo {
	var $config = null;

	function __construct($config) {
		$this->config = $config;
	}

	static function newInstance($config) {
		return new UserBo($config);
	}

	function _getFilePath() {
		return "user.json";
	}

	function checkRootPassword($password) {
		return $this->checkPassword("root", $password);
	}

	function checkPassword($login, $password) {
		$user = UserBo::sendCommand("cat /etc/shadow | grep $login");

		$re = "/^[a-z]+:\\W1\\W([\\.0-9a-z]*)\\W([0-9a-z\\/\\.]*):/i";

		preg_match($re, $user, $matches, PREG_OFFSET_CAPTURE);
		$salt = $matches[1][0];

		$linuxPassword = $this->getLinuxPassword($password, $salt);

		return (strpos($user, $linuxPassword) !== false);
	}

	function getLinuxPassword($password, $salt) {
		UserBo::sendCommand("echo $password > test.pwd");
		$linuxPassword = trim(UserBo::sendCommand("openssl passwd -1 -salt $salt -in test.pwd"));
		UserBo::sendCommand("rm test.pwd");

		return $linuxPassword;
	}

	function getUser() {
		if (file_exists($this->_getFilePath())) {
			$content = file_get_contents($this->_getFilePath());
			return json_decode($content, true);
		}

		$defaultUser = array();

//		$defaultUser["password"] = $this->config["parpaing"]["default_password"];
		$defaultUser["language"] = $this->config["parpaing"]["default_language"];

		return $defaultUser;
	}

	function saveUser($user) {
		$content = json_encode($user);
		file_put_contents($this->_getFilePath(), $content);
	}

	function setLanguage($language) {
		$user = $this->getUser();
		$user["language"] = $language;

		$this->saveUser($user);
	}

	function setPassword($oldPassword, $newPassword) {
		$user = UserBo::sendCommand("cat /etc/shadow | grep root");

		$re = "/^[a-z]+:\\W1\\W([0-9a-z]*)\\W([0-9a-z\\/\\.]*):/i";

		preg_match($re, $user, $matches, PREG_OFFSET_CAPTURE);
		$salt = $matches[1][0];

		$oldLinuxPassword = $this->getLinuxPassword($oldPassword, $salt);
		$newLinuxPassword = $this->getLinuxPassword($newPassword, $salt);

		$content = UserBo::sendCommand("cat /etc/shadow");
		$content = str_replace($oldLinuxPassword, $newLinuxPassword, $content);

		file_put_contents("/etc/shadow", $content);

// 		$user = $this->getUser();
// 		$user["password"] = $password;

// 		$this->saveUser($user);
	}

	static function sendCommand($cmd) {
		// TODO add security

		return shell_exec($cmd);
	}
}
?>