<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conf extends Model
{
    public $guarded = [];

	public $casts = [
		'default_keepdata' => 'boolean'
	];

	public function defaultCountry() {
		return $this->belongsTo(\App\Country::class);
	}

	public function defaultRegion() {
		return $this->belongsTo(\App\Region::class);
	}
}
