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
$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
set_time_limit(0);

require_once 'config/config.php';
require_once 'vpn/api_client.php';

// Make the api call

//set POST variables
//$url = 'https://www.armagnet.fr/vpn/api.php';
//$url = 'http://127.0.0.1/vpn/api.php';
$apiClient = new ArmagnetVpnApiClient($config["armagnet"]["api_url"]);

$person = array();
$account = array();

$account["login"] = $_REQUEST["loginInput"];
$account["password"] = $_REQUEST["passwordInput"];

if (isset($_REQUEST["hasAccount"]) && $_REQUEST["hasAccount"] == 1) {
	$response = $apiClient->authenticate($account);

	// If bad login, don't go further
	if (isset($response["ko"])) {
		echo json_encode(array("ko" => "ko", "message" => $response["message"]));
		exit();
	}

	$person = $response["person"];
}
else {
	$account["confirmPassword"] = $_REQUEST["confirmInput"];

	if ($account["confirmPassword"] != $account["password"]) {
		echo json_encode(array("ko" => "ko", "message" => "notSamePasswords", "focus" => "confirmInput"));
		exit();
	}

	$person["mail"] = $_REQUEST["emailInput"];
	$person["firstname"] = $_REQUEST["firstnameInput"];
	$person["lastname"] = $_REQUEST["lastnameInput"];
	$person["address_1"] = $_REQUEST["addressInput"];
	$person["address_2"] = $_REQUEST["address2Input"];
	$person["zip_code"] = $_REQUEST["zipcodeInput"];
	$person["city"] = $_REQUEST["cityInput"];

	if (!$person["lastname"]) {
		echo json_encode(array("ko" => "ko", "message" => "lastnameMandatory", "focus" => "lastnameInput"));
		exit();
	}

	if (!$person["firstname"]) {
		echo json_encode(array("ko" => "ko", "message" => "firstnameMandatory", "focus" => "firstnameInput"));
		exit();
	}

	if (!$person["mail"]) {
		echo json_encode(array("ko" => "ko", "message" => "mailMandatory", "focus" => "emailInput"));
		exit();
	}

	// If already exists, don't go further
	$response = $apiClient->createAccount($account, $person);

	if (isset($response["ko"])) {
		echo json_encode(array("ko" => "ko", "message" => $response["message"]));
		exit();
	}
}

$openSslConfig = array(
		"digest_alg" => "sha512",
		"private_key_bits" => 4096,
		"private_key_type" => OPENSSL_KEYTYPE_RSA,
);

// Create the private and public key
$res = openssl_pkey_new($openSslConfig);

$dn = array(
		"countryName" => "FR",
		"stateOrProvinceName" => "France",
		"organizationName" => "Armagnet",
		"commonName" => $person["firstname"] . " " . $person["lastname"],
		"emailAddress" => $person["mail"]
);

// Create the Certificate Signature Request
$csr = openssl_csr_new($dn, $res);
openssl_csr_export($csr, $csrout);
$result = $apiClient->postCsr($account, $csrout);

$vpnHash = $result["vpn_id"];
$keyPath = $config["openvpn"]["config"] . "_$vpnHash";

$defaultPassword = "withapasswordforthisphase";

// Extract the private key from $res to $privKey
// No password for openvpn in deamon mode
openssl_pkey_export($res, $privKey, $defaultPassword);

// Find a better way
file_put_contents($keyPath . ".pkey", $privKey);
shell_exec("openssl pkey -in $keyPath" . ".pkey -passin pass:$defaultPassword -out $keyPath" . ".key");
unlink("$keyPath" . ".pkey");

echo json_encode(array("ok" => "ok", "vpnHash" => $vpnHash));

?>