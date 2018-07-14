<?php

namespace App\Services;

use App\Conf;

class SettingService {
    public function get($key) {
        return Conf::first()->{$key};
    }
}
