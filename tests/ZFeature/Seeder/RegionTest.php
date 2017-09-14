<?php

namespace Tests\Unit\Seeder;

use Tests\TestCase;
use \App\Region;

class RegionTest extends TestCase
{
	/** @test */
	public function it_seeds_all_confs() {
		$this->runMigration('regions_table');
		$this->runSeeder(\RegionSeeder::class);

		$this->assertEquals(16, Region::count());
		$this->assertEquals(
			['Baden-Württemberg', 'Bayern', 'Berlin', 'Brandenburg', 'Bremen', 'Hamburg', 'Hessen', 'Mecklenburg-Vorpommern', 'Niedersachsen', 'Nordrhein-Westfalen', 'Rheinland-Pfalz', 'Saarland', 'Sachsen', 'Sachsen-Anhalt', 'Schleswig-Holstein', 'Thüringen'], 
			Region::get()->pluck('title')->toArray()
		);
	}
}
