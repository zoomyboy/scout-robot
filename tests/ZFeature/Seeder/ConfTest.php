<?php

namespace Tests\Unit\Seeder;

use Tests\TestCase;
use \App\Conf as Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConfTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function it_seeds_all_confs() {
		$seeder = (new \CountrySeeder())->run();
		$seeder = (new \ConfSeeder())->run();
		$this->assertEquals(1, Model::get()->count());

		$conf = Model::first();

		$this->assertEquals('Deutschland', $conf->defaultCountry->title);
	}
}
