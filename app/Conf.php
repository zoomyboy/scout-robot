<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conf extends Model
{
    public $guarded = [];

	public $timestamps = false;

	public $with = ['defaultCountry', 'defaultRegion', 'files', 'deadlineunit', 'defaultWay'];

	public $hidden = ['namiPassword'];

	public $casts = [
		'default_keepdata' => 'boolean',
		'default_sendnewspaper' => 'boolean',
		'includeFamilies' => 'boolean',
		'namiEnabled' => 'boolean'
	];

	public function defaultCountry() {
		return $this->belongsTo(\App\Country::class);
	}

	public function defaultWay() {
		return $this->belongsTo(\App\Way::class);
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

	public static function boot() {
		static::updating(function($model) {
			if ($model->namiEnabled == false) {
				$model->namiPassword = '';
				$model->namiUser = '';
				$model->namiGroup = '';
			}
		});
	}
}
