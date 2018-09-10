<?php

namespace App\Traits;

trait HasNamiFallbacks {
    public function getGenderFallbackAttribute() {
        return $this->gender
            ? $this->gender->nami_id
            : \App\Gender::where('is_null', true)->first()->nami_id
        ;
    }
}
