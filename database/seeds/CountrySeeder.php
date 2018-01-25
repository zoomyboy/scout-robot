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
		Country::create(['title' => 'Deutschland', 'nami_id' => 1, 'nami_title' => 'Deutschland']);
		Country::create(['title' => 'Algerien', 'nami_id' => 2, 'nami_title' => 'Algerien']);
		Country::create(['title' => 'Belgien', 'nami_id' => 3, 'nami_title' => 'Belgien']);
		Country::create(['title' => 'Brasilien', 'nami_id' => 4, 'nami_title' => 'Brasilien']);
		Country::create(['title' => 'Dänemark', 'nami_id' => 5, 'nami_title' => 'Dänemark']);
		Country::create(['title' => 'Finnland', 'nami_id' => 6, 'nami_title' => 'Finnland']);
		Country::create(['title' => 'Frankreich', 'nami_id' => 7, 'nami_title' => 'Frankreich']);
		Country::create(['title' => 'Grossbritanien', 'nami_id' => 8, 'nami_title' => 'Grossbritanien']);
		Country::create(['title' => 'Irland', 'nami_id' => 9, 'nami_title' => 'Irland']);
		Country::create(['title' => 'Island', 'nami_id' => 10, 'nami_title' => 'Island']);
		Country::create(['title' => 'Israel', 'nami_id' => 11, 'nami_title' => 'Israel']);
		Country::create(['title' => 'Italien', 'nami_id' => 12, 'nami_title' => 'Italien']);
		Country::create(['title' => 'Japan', 'nami_id' => 13, 'nami_title' => 'Japan']);
		Country::create(['title' => 'Kanada', 'nami_id' => 14, 'nami_title' => 'Kanada']);
		Country::create(['title' => 'Litauen', 'nami_id' => 15, 'nami_title' => 'Litauen']);
		Country::create(['title' => 'Luxemburg', 'nami_id' => 16, 'nami_title' => 'Luxemburg']);
		Country::create(['title' => 'Niederlande', 'nami_id' => 17, 'nami_title' => 'Niederlande']);
		Country::create(['title' => 'Norwegen', 'nami_id' => 18, 'nami_title' => 'Norwegen']);
		Country::create(['title' => 'Österreich', 'nami_id' => 19, 'nami_title' => 'Österreich']);
		Country::create(['title' => 'Polen', 'nami_id' => 20, 'nami_title' => 'Polen']);
		Country::create(['title' => 'Rumänien', 'nami_id' => 21, 'nami_title' => 'Rumänien']);
		Country::create(['title' => 'Schweiz', 'nami_id' => 22, 'nami_title' => 'Schweiz']);
		Country::create(['title' => 'Schweden', 'nami_id' => 23, 'nami_title' => 'Schweden']);
		Country::create(['title' => 'Slowenien', 'nami_id' => 24, 'nami_title' => 'Slowenien']);
		Country::create(['title' => 'Spanien', 'nami_id' => 25, 'nami_title' => 'Spanien']);
		Country::create(['title' => 'Südafrika', 'nami_id' => 26, 'nami_title' => 'Südafrika']);
		Country::create(['title' => 'Thailand', 'nami_id' => 27, 'nami_title' => 'Thailand']);
		Country::create(['title' => 'Ungarn', 'nami_id' => 28, 'nami_title' => 'Ungarn']);
		Country::create(['title' => 'Vatikanstadt', 'nami_id' => 29, 'nami_title' => 'Vatikanstadt']);
		Country::create(['title' => 'USA', 'nami_id' => 30, 'nami_title' => 'USA']);
		Country::create(['title' => 'Indien', 'nami_id' => 31, 'nami_title' => 'Indien']);
		Country::create(['title' => 'Polen', 'nami_id' => 32, 'nami_title' => 'Polen']);
		Country::create(['title' => 'CV', 'nami_id' => 33, 'nami_title' => 'CV']);
		Country::create(['title' => 'Ausland', 'nami_id' => 34, 'nami_title' => 'Ausland']);
		Country::create(['title' => 'Malaysia', 'nami_id' => 35, 'nami_title' => 'Malaysia']);
		Country::create(['title' => 'Tunesien', 'nami_id' => 36, 'nami_title' => 'Tunesien']);
    }
}
