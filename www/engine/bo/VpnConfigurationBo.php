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

class VpnConfigurationBo {
	var $config = null;

	function __construct($config) {
		$this->config = $config;
	}

	static function newInstance($config) {
		return new VpnConfigurationBo($config);
	}

	function _getFilePath() {
		$path = $this->config["openvpn"]["path"];
		return "$path/vpn_configurations.json";
	}

	function saveConfigurations($configurations) {
		$content = json_encode($configurations);

// 		error_log("Save configuration : " . $this->_getFilePath());
// 		error_log("save : $content");

		file_put_contents($this->_getFilePath(), $content);
	}

	function getConfigurations() {
		$configurations = array();

		if (file_exists($this->_getFilePath())) {
			$content = file_get_contents($this->_getFilePath());
			$configurations = json_decode($content, true);
		}

		return $configurations;
	}

	function getConfigurationById($id) {

		$configurations = $this->getConfigurations();

		foreach($configurations as $index => $currentConfiguration) {
			if ($currentConfiguration["id"] == $id) {

				return $currentConfiguration;
			}
		}

		return null;
	}

	function addConfiguration(&$configuration) {
		$configurations = $this->getConfigurations();

		if (!isset($configuration["active"])) {
			$configuration["active"] = false;
		}

		$configurations[] = $configuration;
		$this->saveConfigurations($configurations);
	}

	function updateConfiguration(&$configuration) {

		$configurations = $this->getConfigurations();

		foreach($configurations as $index => $currentConfiguration) {
			if ($currentConfiguration["id"] == $configuration["id"]) {

				$configurations[$index] = $configuration;

				break;
			}
		}

		$this->saveConfigurations($configurations);

	}

	/**
	 * If the given configuration is not <code>NULL</code>, it's switched to active.
	 * Others configurations are switched to not active.
	 *
	 * @param VpnConfiguration $configuration The configuration to switch active, otherwise all the configurations
	 * are marked as not active
	 */
	function activate(&$configuration) {

		$configurations = $this->getConfigurations();

		foreach($configurations as $index => $currentConfiguration) {
			if ($configuration && $currentConfiguration["id"] == $configuration["id"]) {
				$configurations[$index]["active"] = true;
				$configuration["active"] = true;
			}
			else {
				$configurations[$index]["active"] = false;
			}
		}

		$this->saveConfigurations($configurations);

	}

	function deleteConfiguration($configuration) {

		$configurations = $this->getConfigurations();

		foreach($configurations as $index => $currentConfiguration) {
			if ($currentConfiguration["id"] == $configuration["id"]) {

				unset($configurations[$index]);

				break;
			}
		}

		$this->saveConfigurations($configurations);
	}
}
?>