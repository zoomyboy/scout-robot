<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasTitle
{
    public function scopeTitle($q, $title) {
        return $q->where('title', $title);
    }
}
