<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Usergroup extends Model
{
    use HasTitle;

	public $timestamps = false;

    public $fillable = ['title'];

	public function rights() {
		return $this->belongsToMany(\App\Right::class);
	}

	public function hasRight($key) {
		return $this->rights()->where('key', $key)->first() != null;
	}

	public function users() {
		return $this->hasMany(\App\User::class);
	}
}
