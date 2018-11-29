<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Config;
use Setting;

trait SeedsDatabase {
    public function createCountries() {
        Config::set('seed.default_country', 'Englisch');
        \App\Country::create(['nami_title' => 'NDeutsch', 'nami_id' => 1054, 'title' => 'Deutsch']);
        \App\Country::create(['nami_title' => 'NEng', 'nami_id' => 455, 'title' => 'Englisch']);
    }

    public function seedConfig() {
        $this->createCountries();
        $this->runSeeder(\ConfSeeder::class);
    }

    public function beforeAuthUserCreated() {
        $this->runSeeder(\UsergroupSeeder::class);
    }
}
