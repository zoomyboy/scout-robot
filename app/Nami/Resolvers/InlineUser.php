<?php

namespace App\Nami\Resolvers;

use Setting;
use App\Nami\Interfaces\UserResolver;

/**
 * Gets the current user and group from the class itself
 * to login to NaMi
 */
class InlineUser implements UserResolver {
    public $user;
    public $password;
    public $group;

    public function __construct($user, $password, $group) {
        $this->user = $user;
        $this->password = $password;
        $this->group = $group;
    }

    public function getUsername() {
        return $this->user;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getGroup() {
        return $this->group;
    }

    public function hasCredentials() {
        return (bool)$this->user && (bool)$this->password;
    }
}
