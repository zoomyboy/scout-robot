<?php

namespace App\Nami\Exceptions;

class TooManyLoginAttemptsException extends \Exception {

	public $time;

	public function __construct($msg, $e) {
		parent::__construct($msg, $e);
	}

	public function setTime($time) {
		$this->time = $time;
	}
}
