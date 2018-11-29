<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Right extends Model
{
    use HasTitle;

	public $timestamps = false;
	public $fillable = ['key', 'title', 'help'];
}
