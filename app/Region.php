<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasTitle;

	public $timestamps = false;

    public $fillable = ['title', 'nami_title', 'nami_id', 'is_null'];

	public $casts = [
		'is_null' => 'boolean'
	];
}
