<?php

namespace App\Facades\NaMi;

use Illuminate\Support\Facades\Facade;

class NaMiMembership extends Facade {
	public static function getFacadeAccessor() {
		return 'nami.membership';
	}
}
