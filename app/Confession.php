<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Confession extends Model
{
    use HasTitle;

    public $fillable = ['title', 'nami_id', 'nami_title'];

	public $timestamps = false;
}
