<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $fillable = ['title', 'is_group', 'nami_id', 'group_order'];

	public $casts = [
		'group_order' => 'integer',
		'is_group' => 'boolean',
		'nami_id' => 'integer'
	];

	public function activities() {
		return $this->belongsToMany(Activity::class);
	}
}
