<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usergroup extends Model
{
	public $timestamps = false;

    public $fillable = ['title'];

	public function rights() {
		return $this->belongsToMany(\App\Right::class);
	}

	public function hasRight($key) {
		return $this->rights()->where('key', $key)->first() != null;
	}
}
