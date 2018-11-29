<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasTitle;

    public $fillable = ['nami_title', 'title', 'nami_id'];

	public $timestamps = false;
}
