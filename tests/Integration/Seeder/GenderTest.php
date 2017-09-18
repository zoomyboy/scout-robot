<?php

namespace Tests\Integration\Seeder;

use Tests\TestCase;
use \App\Gender;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenderTest extends TestCase
{
	/** @test */
	public function it_seeds_all_confs() {
		$this->runMigration('genders_table');
		$this->runSeeder(\GenderSeeder::class);

		$this->assertEquals(2, Gender::count());
		$this->assertEquals(
			['Männlich', 'Weiblich'], 
			Gender::get()->pluck('title')->toArray()
		);
	}
}
