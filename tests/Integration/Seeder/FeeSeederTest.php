<?php

namespace Tests\Integration\Seeder;

use Tests\IntegrationTestCase;

class FeeSeederTest extends IntegrationTestCase {
	public function setUp() {
		parent::setUp();

		$this->runSeeder('FeeSeeder');
	}

	/** @test */
	public function it_seeds_the_fees() {
		$fee = \App\Fee::first();

		$this->assertEquals('Voller Beitrag', $fee->title);
		$this->assertEquals(1, $fee->nami_id);
	}
}
