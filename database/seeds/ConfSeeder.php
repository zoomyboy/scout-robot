<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Schema\Blueprint;
use App\Conf;

class ConfSeeder extends Seeder
{
	private $confs = [
		'default_country_id',
		'default_region_id',
		'default_keepdata',
		'default_sendnewspaper',
		'letterKontoName',
		'letterIban',
		'letterBic',
		'letterZweck',
		'includeFamilies',
		'deadlinenr',
		'deadlineunit_id'
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Schema::table('confs', function(Blueprint $table) {
			foreach($this->confs as $key) {
				$table->string($key)->nullable();
			}
		});

		if (is_null(\App\Country::where('code', config('seed.default_country'))->first())) {
			throw new \Exception('Default country  not found in countries table. You should run a seeder or create the table!');
		}

		Conf::create([
			'default_country_id' => \App\Country::where('code', config('seed.default_country'))->first()->id,
			'default_region_id' => null,
			'default_keepdata' => false,
			'default_sendnewspaper' => false
		]);
    }
}
