<?php

namespace App\NaMi\Resolvers;

use Setting;
use App\NaMi\Interfaces\UserResolver;

/**
 * Gets the current user and group from global ScoutRobot config
 * to login to NaMi
 */
class CurrentUser implements UserResolver {
    public function getUsername() {
        if (!Setting::get('namiEnabled')) {return false;}
        return Setting::get('namiUser');
    }

    public function getPassword() {
        if (!Setting::get('namiEnabled')) {return false;}
        return Setting::get('namiPassword');
    }

    public function getGroup() {
        if (!Setting::get('namiEnabled')) {return false;}
        return Setting::get('namiGroup');
    }
}
