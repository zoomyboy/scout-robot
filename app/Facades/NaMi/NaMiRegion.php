<?php

namespace App\Facades\NaMi;

use Illuminate\Support\Facades\Facade;

class NaMiRegion extends Facade {
	public static function getFacadeAccessor() {
		return 'nami.region';
	}
}
