<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    public $fillable = ['title','nami_title', 'nami_id', 'is_null'];

	public $casts = [
		'is_null' => 'boolean'
	];
}
