<?php

namespace App\Nami\Traits;

trait HasNamiId {
    /**
     * Filter by members that are synched with nami id
     */
    public function scopeNami($q, $id)
    {
        return $q->where('nami_id', $id);
    }
}
