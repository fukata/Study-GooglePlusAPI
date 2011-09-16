<?php

class Google_plus_api {

	function __construct($params=array()) {
		foreach ($params as $key => $val) {
			$this->{$key} = $val;
		}

		$this->oauth = new OAuth($this->client_id, $this->client_secret);
		$this->oauth->setVersion('2.0');
	}

	function authorize() {
		$url = sprintf('https://accounts.google.com/o/oauth2/auth?client_id=%s&redirect_uri=%s&scope=%s&response_type=%s&state=%s',
			$this->client_id,
			$this->callback_url,
			'https://www.google.com/m8/feeds/',
			'code',
			''
		);

		redirect($url);
	}

	function access_token($code) {
		$url = sprintf('https://accounts.google.com/o/oauth2/token');
		$params = array(
			'code' => $code,
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'redirect_uri' => $this->callback_url,
			'grant_type' => 'authorization_code'
		);
		$this->oauth->fetch($url, $params, OAUTH_HTTP_METHOD_POST, array(
			'application/x-www-form-urlencoded'
		));
		return json_decode($this->oauth->getLastResponse());
	}

	function refresh_token($refresh_token) {
		$url = sprintf('https://accounts.google.com/o/oauth2/token');
		$params = array(
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'grant_type' => $refresh_token, 
			'grant_type' => 'refresh_code'
		);
		$this->oauth->fetch($url, $params, OAUTH_HTTP_METHOD_POST, array(
			'application/x-www-form-urlencoded'
		));
		return json_decode($this->oauth->getLastResponse());
	}

	function feeds($access_token) {
		$url = sprintf('https://www.google.com/m8/feeds/contacts/default/full?access_token=%s', $access_token);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		return curl_exec($ch);
	}
}
