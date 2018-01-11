<?php

namespace App\Facades\NaMi;

use Illuminate\Support\Facades\Facade;

class NaMiMember extends Facade {
	public static function getFacadeAccessor() {
		return 'nami.member';
	}
}
