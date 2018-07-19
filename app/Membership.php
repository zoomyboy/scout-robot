<?php

namespace App;

use App\Events\MembershipCreated;
use App\Nami\Traits\HasNamiId;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasNamiId;

    public $fillable = ['activity_id', 'group_id', 'created_at', 'nami_id'];

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
