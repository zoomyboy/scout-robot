<?php

namespace App\Facades\NaMi;

use Illuminate\Support\Facades\Facade;

class NaMiGroup extends Facade {
	public static function getFacadeAccessor() {
		return 'nami.group';
	}
}
