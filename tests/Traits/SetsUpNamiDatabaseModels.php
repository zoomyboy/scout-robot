<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Config;

trait SetsUpNamiDatabaseModels {
    public function setupNamiDatabaseModels() {
        $this->createCountries();

        \App\Nationality::create(['nami_title' => 'NDeutsch', 'nami_id' => 334, 'title' => 'Deutsch']);
        \App\Nationality::create(['nami_title' => 'NEng', 'nami_id' => 584, 'title' => 'Englisch']);

        \App\Way::create(['title' => 'Way1']);
        \App\Way::create(['title' => 'Way2']);

        \App\Gender::create(['nami_title' => 'M', 'nami_id' => 100, 'title' => 'M', 'is_null' => false]);
        \App\Gender::create(['nami_title' => 'W', 'nami_id' => 101, 'title' => 'W', 'is_null' => false]);

        \App\Region::create(['nami_title' => 'NRW', 'nami_id' => 200, 'title' => 'NRW', 'is_null' => false]);
        \App\Region::create(['nami_title' => 'BW', 'nami_id' => 201, 'title' => 'BW', 'is_null' => false]);

        \App\Confession::create(['nami_title' => 'RK', 'nami_id' => 300, 'title' => 'RK']);
        \App\Confession::create(['nami_title' => 'E', 'nami_id' => 301, 'title' => 'E']);

        $this->runSeeder(\FeeSeeder::class);
        $this->runSeeder(\SubscriptionSeeder::class);

        $this->runSeeder(\ConfSeeder::class);
    }

    public function setUpActivityGroups() {
        $this->runSeeder(\ActivitySeeder::class);
    }
}
