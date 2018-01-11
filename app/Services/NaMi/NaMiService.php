<?php

namespace App\Services\NaMi;

use App\Conf;

class NaMiService {

	protected $cookie;
	protected $baseUrl;
	protected $config;

	public function __construct() {
		$this->cookie = config('nami.cookie');
		$this->baseUrl = config('nami.baseurl');
		$this->config = Conf::first();
	}

	public function login() {
		$handle = curl_init($this->baseUrl.'/ica/pages/login.jsp');
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt ($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($handle, CURLOPT_COOKIEJAR, $this->cookie);
		$body = curl_exec($handle);
		curl_close($handle);

		$handle = curl_init($this->baseUrl.'/ica/rest/nami/auth/manual/sessionStartup');
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, 'Login=API&redirectTo=./app.jsp&username='.$this->username().'&password='.$this->password());
		curl_setopt ($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($handle, CURLOPT_COOKIEJAR, $this->cookie);
		curl_setopt ($handle, CURLOPT_COOKIEFILE, $this->cookie);
		$body = curl_exec($handle);
		curl_close($handle);
	}

	public function username() {
		return $this->config->namiUser;
	}

	public function password() {
		return $this->config->namiPassword;
	}

	public function get($url) {
		$this->login();

		$handle = curl_init($this->baseUrl.$url);

		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($handle, CURLOPT_POSTFIELDS, '');
		curl_setopt ($handle, CURLOPT_POST, 0);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($handle, CURLOPT_COOKIEJAR, $this->cookie);
		curl_setopt ($handle, CURLOPT_COOKIEFILE, $this->cookie);
		$body = curl_exec($handle);
		curl_close($handle);

		return json_decode($body);
	}
}
