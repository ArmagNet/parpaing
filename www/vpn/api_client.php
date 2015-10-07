<?php

class ArmagnetVpnApiClient {
	var $apiUrl = null;

	function __construct($apiUrl) {
		$this->apiUrl = $apiUrl;
	}

	function authenticate($account) {
		$fields = array(
				'account' => urlencode(json_encode($account))
		);

		return $this->_post("authenticate", $fields);
	}

	function createAccount($account, $person) {
		$fields = array(
				'account' => urlencode(json_encode($account)),
				'person' => urlencode(json_encode($person))
		);

		return $this->_post("createAccount", $fields);
	}

	function retrieveConfigurations($account) {
		$fields = array(
				'account' => urlencode(json_encode($account))
		);

		return $this->_post("retrieveConfigurations", $fields);
	}

	function postCsr($account, $csr) {
		$fields = array(
				'account' => urlencode(json_encode($account)),
				//		'user' => urlencode(json_encode($user)),
				'csr' => urlencode($csr)
		);

		return $this->_post("postCSR", $fields);
	}

	function _post($method, $fields) {

		$url = $this->apiUrl;
		if (strpos($url, "?") === false) {
			$url .= "?method=$method";
		}
		else {
			$url .= "&method=$method";
		}

		//url-ify the data for the POST
		$fieldsString = http_build_query($fields);

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data, and say that we want the result returnd not printed
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		error_log("call method : $method");
		error_log("call fields : $fieldsString");

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		error_log($result);

		// json decode the result, the api has json encoded result
		$result = json_decode($result, true);

		error_log(print_r($result, true));

		return $result;
	}
}
?>