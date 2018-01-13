<?php

use Illuminate\Database\Seeder;
use App\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Region::create(['title' => 'Baden-Württemberg', 'nami_title' => 'Baden-Württemberg (Deutschland)', 'nami_id' => '1', 'is_null' => 'false']);
		Region::create(['title' => 'Bayern', 'nami_title' => 'Bayern (Deutschland)', 'nami_id' => '2', 'is_null' => 'false']);
		Region::create(['title' => 'Berlin', 'nami_title' => 'Berlin (Deutschland)', 'nami_id' => '3', 'is_null' => 'false']);
		Region::create(['title' => 'Brandenburg', 'nami_title' => 'Brandenburg (Deutschland)', 'nami_id' => '4', 'is_null' => 'false']);
		Region::create(['title' => 'Bremen', 'nami_title' => 'Bremen (Deutschland)', 'nami_id' => '5', 'is_null' => 'false']);
		Region::create(['title' => 'Hamburg', 'nami_title' => 'Hamburg (Deutschland)', 'nami_id' => '6', 'is_null' => 'false']);
		Region::create(['title' => 'Hessen', 'nami_title' => 'Hessen (Deutschland)', 'nami_id' => '7', 'is_null' => 'false']);
		Region::create(['title' => 'Mecklenburg-Vorpommern', 'nami_title' => 'Mecklenburg-Vorpommern (Deutschland)', 'nami_id' => '8', 'is_null' => 'false']);
		Region::create(['title' => 'Niedersachsen', 'nami_title' => 'Niedersachsen (Deutschland)', 'nami_id' => '9', 'is_null' => 'false']);
		Region::create(['title' => 'Nordrhein-Westfalen', 'nami_title' => 'Nordrhein-Westfalen (Deutschland)', 'nami_id' => '10', 'is_null' => 'false']);
		Region::create(['title' => 'Rheinland-Pfalz', 'nami_title' => 'Rheinland-Pfalz (Deutschland)', 'nami_id' => '11', 'is_null' => 'false']);
		Region::create(['title' => 'Saarland', 'nami_title' => 'Saarland (Deutschland)', 'nami_id' => '12', 'is_null' => 'false']);
		Region::create(['title' => 'Sachsen', 'nami_title' => 'Sachsen (Deutschland)', 'nami_id' => '13', 'is_null' => 'false']);
		Region::create(['title' => 'Sachsen Anhalt', 'nami_title' => 'Sachsen Anhalt (Deutschland)', 'nami_id' => '14', 'is_null' => 'false']);
		Region::create(['title' => 'Schleswig-Holstein', 'nami_title' => 'Schleswig-Holstein (Deutschland)', 'nami_id' => '15', 'is_null' => 'false']);
		Region::create(['title' => 'Thüringen', 'nami_title' => 'Thüringen (Deutschland)', 'nami_id' => '16', 'is_null' => 'false']);
		Region::create(['title' => 'Nicht-DE (Ausland)', 'nami_title' => 'Nicht-DE (Ausland)', 'nami_id' => '23', 'is_null' => 'true']);
    }
}
