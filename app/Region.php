<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
	public $timestamps = false;

    public $fillable = ['title', 'nami_title', 'nami_id', 'is_null'];
}
