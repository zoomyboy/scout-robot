<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $fillable = ['title'];

	public static function default() {
		return conf()->defaultRegion;
	}
}
