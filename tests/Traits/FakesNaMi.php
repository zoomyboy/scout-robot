<?php

namespace Tests\Traits;

use Tests\Utilities\NaMiFake;

trait FakesNaMi {
    public function nami() {
        return new NaMiFake();
    }
}
