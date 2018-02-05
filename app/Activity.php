<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	public $fillable = ['title', 'nami_id', 'is_payable'];

    public $casts = [
        'is_payable' => 'boolean',
        'nami_id' => 'integer'
    ];

	public function groups() {
		return $this->belongsToMany(Group::class);
	}
}
