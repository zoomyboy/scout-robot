<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public $fillable = ['title', 'type'];

	public function scopeOfType($q, $type) {
		return $q->where('type', $type);
	}
}
