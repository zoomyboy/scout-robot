<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountrySeeder extends Seeder
{
	public $lang = [
		'DE' => 'deutsch'
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = require(base_path().'/vendor/umpirsky/country-list/data/'.config('app.locale').'/country.php');

		foreach($data as $key => $value) {
			Country::create(['code' => $key, 'title' => $value, 'lang' => $this->lang[$key] ?? '']);
		}
    }
}
