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

$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'config/config.php';
require_once 'vpn/api_client.php';

$account = array();

$account["login"] = $_REQUEST["loginInput"];
$account["password"] = $_REQUEST["passwordInput"];

// Make the api call

//set POST variables
//$url = 'https://www.armagnet.fr/vpn/api.php';
//$url = 'http://127.0.0.1/vpn/api.php';
$apiClient = new ArmagnetVpnApiClient($config["armagnet"]["api_url"]);
$result = $apiClient->retrieveConfigurations($account);

//error_log($result["json"]["dev"]);

//error_log(print_r($result, true));

$_SESSION["VPN_CONFIGURATIONS"] = json_encode($result["configurations"]);

foreach($result["configurations"] as $index => $configuration) {
	unset($result["configurations"][$index]["key"]);
	unset($result["configurations"][$index]["cacrt"]);
	unset($result["configurations"][$index]["dh"]);
//	unset($result["configurations"][$index]["key"]);
}

echo json_encode($result);

?>