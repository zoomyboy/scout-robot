<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $fillable = ['title', 'amount'];

	public function fee() {
		return $this->belongsTo(Fee::class);
	}
}
