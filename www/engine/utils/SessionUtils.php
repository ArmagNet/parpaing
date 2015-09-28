<?php /*
	Copyright 2014 Cédric Levieux, Jérémy Collot, ArmagNet

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

class SessionUtils {

	static function getLanguage($session) {
		if (isset($session["language"]) && $session["language"]) {
			return $session["language"];
		}

		global $config;

		if (isset($config["default_language"]) && $config["default_language"]) {
			return $config["default_language"];
		}

		return "fr";
	}

	static function setLanguage($language, &$session) {
		$session["language"] = $language;
	}

	static function logout(&$session) {
		session_destroy();
		session_start();
	}

	static function login(&$session) {
		$session["is_connected"] = true;
	}

	static function isConnected($session) {
		return isset($session["is_connected"]) && $session["is_connected"];
	}
}
?>