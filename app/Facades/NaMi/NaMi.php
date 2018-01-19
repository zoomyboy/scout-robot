<?php

namespace App\Facades\NaMi;

use Illuminate\Support\Facades\Facade;

class NaMi extends Facade {
	public static function getFacadeAccessor() {
		return 'nami';
	}
}
