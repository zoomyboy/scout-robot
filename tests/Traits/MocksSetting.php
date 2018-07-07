<?php

namespace Tests\Traits;

use App\Facades\Setting;

trait MocksSetting {
    public $setting;

    public function setting($key, $value) {
        Setting::shouldReceive('get')->with($key)->andReturn($value);
    }
}

