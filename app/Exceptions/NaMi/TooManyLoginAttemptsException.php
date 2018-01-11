<?php

namespace App\Exceptions\NaMi;

class TooManyLoginAttemptsException extends \Exception {

	public $time;

	public function __construct($msg, $e) {
		parent::__construct($msg, $e);
	}

	public function setTime($time) {
		$this->time = $time;
	}
}
