<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $fillable = ['nami_title', 'title', 'nami_id'];

	public $timestamps = false;
}
