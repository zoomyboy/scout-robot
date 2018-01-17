<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	public $fillable = ['title', 'nami_id', 'is_payable'];

	public function groups() {
		return $this->belongsToMany(Group::class);
	}
}
