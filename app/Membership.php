<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\MembershipCreated;

class Membership extends Model
{
    public $fillable = ['activity_id', 'group_id', 'created_at', 'nami_id'];

	public $events = [
		'created' => MembershipCreated::class
	];

	public function activity() {
		return $this->belongsTo(Activity::class);
	}

	public function group() {
		return $this->belongsTo(Group::class);
	}

	public function member() {
		return $this->belongsTo(Member::class);
	}
}
