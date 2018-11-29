<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasTitle;

    public $fillable = ['title', 'amount'];

	public $casts = [
		'amount' => 'integer'
	];

	public function fee() {
		return $this->belongsTo(Fee::class);
	}
}
