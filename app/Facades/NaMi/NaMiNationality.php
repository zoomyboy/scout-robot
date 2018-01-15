<?php

namespace App\Facades\NaMi;

use Illuminate\Support\Facades\Facade;

class NaMiNationality extends Facade {
	public static function getFacadeAccessor() {
		return 'nami.nationality';
	}
}
