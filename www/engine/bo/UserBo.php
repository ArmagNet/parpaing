<?php
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

	function getUser() {
		if (file_exists($this->_getFilePath())) {
			$content = file_get_contents($this->_getFilePath());
			return json_decode($content, true);
		}

		$defaultUser = array();

		$defaultUser["password"] = $this->config["parpaing"]["default_password"];
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

	function setPassword($password) {
		$user = $this->getUser();
		$user["password"] = $password;

		$this->saveUser($user);
	}
}
?>