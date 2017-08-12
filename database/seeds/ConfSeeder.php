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
		'default_sendnewspaper'
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

		Conf::create([
			'default_country_id' => \App\Country::where('title', 'Deutschland')->first()->id
		]);
    }
}
