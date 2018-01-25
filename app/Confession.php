<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Confession extends Model
{
    public $fillable = ['title', 'nami_id', 'nami_title'];

	public $timestamps = false;
}
