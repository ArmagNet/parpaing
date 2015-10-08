<?php
$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

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

if (isset($_REQUEST["isMember"]) && $_REQUEST["isMember"] == 1) {
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

// Extract the public key from $res to $pubKey
//$pubKey = openssl_pkey_get_details($res);
//$pubKey = $pubKey["key"];

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

echo json_encode(array("ok" => "ok"));

?>