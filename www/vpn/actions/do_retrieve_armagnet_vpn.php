<?php
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