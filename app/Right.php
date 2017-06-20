<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Right extends Model
{
	public $timestamps = false;
	public $fillable = ['key', 'title', 'help'];
}	
