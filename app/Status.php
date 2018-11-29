<?php

namespace App;

use App\Traits\HasTitle;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasTitle;

    public $fillable = ['title'];
}
