<?php

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Region::create(['title' => 'Baden-Württemberg']);
        \App\Region::create(['title' => 'Bayern']);
        \App\Region::create(['title' => 'Berlin']);
        \App\Region::create(['title' => 'Brandenburg']);
        \App\Region::create(['title' => 'Bremen']);
        \App\Region::create(['title' => 'Hamburg']);
        \App\Region::create(['title' => 'Hessen']);
        \App\Region::create(['title' => 'Mecklenburg-Vorpommern']);
        \App\Region::create(['title' => 'Niedersachsen']);
        \App\Region::create(['title' => 'Nordrhein-Westfalen']);
        \App\Region::create(['title' => 'Rheinland']);
        \App\Region::create(['title' => 'Saarland']);
        \App\Region::create(['title' => 'Sachsen']);
        \App\Region::create(['title' => 'Sachsen-Anhalt']);
        \App\Region::create(['title' => 'Schleswig-Holstein']);
        \App\Region::create(['title' => 'Thüringen']);
    }
}
