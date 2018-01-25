<?php

namespace App\Facades\NaMi;

use Illuminate\Support\Facades\Facade;

class NaMiCountry extends Facade {
	public static function getFacadeAccessor() {
		return 'nami.country';
	}
}
