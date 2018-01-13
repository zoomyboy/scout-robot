<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $fillable = ['code', 'title', 'nami_id'];

	public $timestamps = false;
}
