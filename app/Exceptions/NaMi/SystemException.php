<?php

namespace App\Exceptions\NaMi;

class SystemException extends \Exception {

	public $response;
	public $url;

	public function __construct($message = '', $error=500) {
		parent::__construct($message, $error);

		return $this;
	}

	public function setResponse($response) {
		$this->response = $response;

		return $this;
	}

	public function setUrl($url) {
		$this->url = $url;

		return $this;
	}
}
