<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $fillable = ['amount', 'nr'];

	public $casts = [
		'amount' => 'int'
	];

	public function status() {
		return $this->belongsTo(\App\Status::class);
	}

	public function member() {
		return $this->belongsTo(\App\Member::class);
	}
}
