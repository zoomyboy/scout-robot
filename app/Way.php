<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Way extends Model
{
    use HasTitle;

	public $fillable = ['title'];
	public $timestamps = false;

    public function member() {
    	return $this->hasMany(Member::class);
    }
}
