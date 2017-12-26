<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Way extends Model
{

	public $fillable = ['title'];
	public $timestamps = false;

    public function member() {
    	return $this->hasMany(Member::class);
    }
}
