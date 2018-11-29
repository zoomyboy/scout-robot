<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    use HasTitle;

    public $fillable = ['title','nami_title', 'nami_id'];
}
