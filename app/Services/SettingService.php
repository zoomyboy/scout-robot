<?php

namespace App\Services;

class SettingService {
    public function get($key) {
        return Conf::first()->{$key};
    }
}
