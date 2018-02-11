<?php

namespace App\Relations;

trait HasSameRelation {
    public function hasSame($fields) {
        $instance = (new static);

        return new HasSame($instance->newQuery(), $this, $fields);
    }
}
