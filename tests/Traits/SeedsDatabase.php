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

    public function createConfigs() {
        $this->createCountries();
        $this->runSeeder(\ConfSeeder::class);
    }

    public function beforeAuthUserCreated() {
        $this->runSeeder(\UsergroupSeeder::class);
    }

    public function createRegions() {
        \App\Region::create(['nami_title' => 'NRW', 'nami_id' => 200, 'title' => 'NRW', 'is_null' => false]);
        \App\Region::create(['nami_title' => 'BW', 'nami_id' => 201, 'title' => 'BW', 'is_null' => false]);
    }

    public function createMembers() {
        $this->runSeeder('GenderSeeder');
        $this->createRegions();
        $this->createConfessions();
        $this->createSubscriptions();
        $this->createNationalities();
        $this->createWays();
        $this->runSeeder('MemberSeeder');
        $this->create('Member');
    }

    public function createConfessions() {
        \App\Confession::create(['nami_title' => 'RK', 'nami_id' => 300, 'title' => 'RK']);
        \App\Confession::create(['nami_title' => 'E', 'nami_id' => 301, 'title' => 'E']);
    }

    public function createWays() {
        \App\Way::create(['title' => 'Way1']);
        \App\Way::create(['title' => 'Way2']);
    }

    public function createNationalities() {
        \App\Nationality::create(['nami_title' => 'NDeutsch', 'nami_id' => 334, 'title' => 'Deutsch']);
        \App\Nationality::create(['nami_title' => 'NEng', 'nami_id' => 584, 'title' => 'Englisch']);
    }

    public function createSubscriptions() {
        $this->runSeeder(\StatusSeeder::class);
        $this->runSeeder(\FeeSeeder::class);
        $this->runSeeder(\SubscriptionSeeder::class);
    }
}
