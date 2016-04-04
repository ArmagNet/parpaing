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

class ArmagnetVpnApiClient {
	var $apiUrl = null;

	function __construct($apiUrl) {
		$this->apiUrl = $apiUrl;
	}

	/**
	 * #Warning using the method may induce invoices. But you need to be registered as a ticket member to do so.
	 *
	 * @param array $account {login, password}
	 * @param string $productCode a product code (vpn_year, vpn_6months, pave_vpn_year)
	 * @return array {ticket (The computed ticket), amount (the invoiced amount for this ticket), product (the product code)}
	 */
	function createTicket($account, $productCode) {
		$fields = array(
				'account' => urlencode(json_encode($account)),
				'product' => $productCode
		);

		return $this->_post("createTicket", $fields);
	}

	function authenticate($account) {
		$fields = array(
				'account' => urlencode(json_encode($account))
		);

		return $this->_post("authenticate", $fields);
	}

	function getSerial($account) {
		$fields = array(
				'account' => urlencode(json_encode($account))
		);

		return $this->_post("getSerial", $fields);
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

	function postCsr($account, $serial, $csr) {
		$fields = array(
				'account' => urlencode(json_encode($account)),
				'serial' => $serial,
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
		// Do not verify the validity of the certificate
		// TODO remove this
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

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