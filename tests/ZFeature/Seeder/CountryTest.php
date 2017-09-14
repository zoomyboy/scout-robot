<?php

namespace Tests\Unit\Seeder;

use Tests\TestCase;
use App\Country;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * This test ensures that all countries are seeded correctly
 * Each country has a Codeand a longer title
 */
class CountryTest extends TestCase
{
	/** @test */
	public function it_seeds_all_confs() {
		$this->runMigration('countries_table');
		$this->runSeeder(\CountrySeeder::class);
		
		$this->assertEquals(253, Country::count());

		$germany = Country::where('title', 'Deutschland')->first();
		$this->assertNotNull($germany);
		$this->assertEquals('DE', $germany->code);

		$mali = Country::where('title', 'Mali')->first();
		$this->assertNotNull($mali);
		$this->assertEquals('ML', $mali->code);
	}
}
