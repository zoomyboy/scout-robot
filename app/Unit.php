<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasTitle;

    public $fillable = ['title', 'type'];

	public function scopeOfType($q, $type) {
		return $q->where('type', $type);
	}
}
