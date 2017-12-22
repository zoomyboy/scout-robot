<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conf extends Model
{
    public $guarded = [];

	public $timestamps = false;

	public $with = ['defaultCountry', 'defaultRegion', 'files', 'deadlineunit'];

	public $casts = [
		'default_keepdata' => 'boolean',
		'default_sendnewspaper' => 'boolean',
		'includeFamilies' => 'boolean'
	];

	public function defaultCountry() {
		return $this->belongsTo(\App\Country::class);
	}

	public function deadlineunit() {
		return $this->belongsTo(\App\Unit::class);
	}

	public function defaultRegion() {
		return $this->belongsTo(\App\Region::class);
	}

	public function files() {
		return $this->morphMany(\Zoomyboy\Fileupload\Image::class, 'model');
	}
}
