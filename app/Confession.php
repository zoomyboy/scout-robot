<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Confession extends Model
{
    public $fillable = ['title', 'nami_id'];

	public $timestamps = false;
}
