<?php

namespace App\Facades\NaMi;

use Illuminate\Support\Facades\Facade;

class NaMi extends Facade {
	public static function fake() {
		static::swap(new \Tests\Utilities\NaMiFake());	
	}

	public static function getFacadeAccessor() {
		return 'nami';
	}
}
