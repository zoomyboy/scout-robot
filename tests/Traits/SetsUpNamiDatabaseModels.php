<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Config;

trait SetsUpNamiDatabaseModels {
    public function setupNamiDatabaseModels() {
        $this->createCountries();

        $this->createNationalities();

        $this->createWays();

        \App\Gender::create(['nami_title' => 'M', 'nami_id' => 100, 'title' => 'M', 'is_null' => false]);
        \App\Gender::create(['nami_title' => 'W', 'nami_id' => 101, 'title' => 'W', 'is_null' => false]);

        $this->createRegions();
        \App\Region::create(['nami_title' => 'NRW', 'nami_id' => 200, 'title' => 'NRW', 'is_null' => false]);
        \App\Region::create(['nami_title' => 'BW', 'nami_id' => 201, 'title' => 'BW', 'is_null' => false]);

        $this->createConfessions();

        $this->createSubscriptions();

        $this->runSeeder(\ConfSeeder::class);
    }

    public function setUpActivityGroups() {
        $this->runSeeder(\ActivitySeeder::class);
    }
}
