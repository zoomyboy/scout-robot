<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    public $fillable = ['activity_id', 'group_id', 'created_at'];

	public function activity() {
		return $this->belongsTo(Activity::class);
	}

	public function group() {
		return $this->belongsTo(Group::class);
	}
}
