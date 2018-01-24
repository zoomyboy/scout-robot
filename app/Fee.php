<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
	public $timestamps = false;

    public $fillable = ['title', 'nami_id'];

	public $casts = [
		'nami_id' => 'integer'
	];
}
