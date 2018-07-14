<?php

namespace App\Nami\Interfaces;

interface UserResolver {
    public function getUsername();
    public function getPassword();
    public function getGroup();
    public function hasCredentials();
}
