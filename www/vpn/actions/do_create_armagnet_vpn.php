<?php
$path = "../../";
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'config/config.php';
require_once 'vpn/api_client.php';

$user = array();
$account = array();

$user["email"] = $_REQUEST["emailInput"];
$user["firstname"] = $_REQUEST["firstnameInput"];
$user["lastname"] = $_REQUEST["lastnameInput"];

$account["login"] = $_REQUEST["loginInput"];
$account["password"] = $_REQUEST["passwordInput"];

$openSslConfig = array(
		"digest_alg" => "sha512",
		"private_key_bits" => 4096,
		"private_key_type" => OPENSSL_KEYTYPE_RSA,
);

// Create the private and public key
$res = openssl_pkey_new($openSslConfig);

// Extract the private key from $res to $privKey
// No password for openvpn in deamon mode
openssl_pkey_export($res, $privKey, "");

// Extract the public key from $res to $pubKey
$pubKey = openssl_pkey_get_details($res);
$pubKey = $pubKey["key"];

$dn = array(
		"countryName" => "FR",
		"stateOrProvinceName" => "France",
//		"localityName" => "Glastonbury",
		"organizationName" => "Armagnet",
//		"organizationalUnitName" => "PHP Documentation Team",
		"commonName" => "Karine Couot",
		"emailAddress" => "tornade@tornade.fr"
);

// Génère la requête de signature de certificat
$csr = openssl_csr_new($dn, $res);

openssl_csr_export($csr, $csrout);

file_put_contents("/home/cedric/OpenVPN/parpaing/.csr", $csrout);
file_put_contents("/home/cedric/OpenVPN/parpaing/.key", $privKey);

// Make the api call

//set POST variables
//$url = 'https://www.armagnet.fr/vpn/api.php';
//$url = 'http://127.0.0.1/vpn/api.php';
$apiClient = new ArmagnetVpnApiClient($config["armagnet"]["api_url"]);
$result = $apiClient->postCsr($account, $csrout);

echo json_encode(array("ok" => "ok", "post" => $result));

?>