<?php

namespace Tests\Integration;

use Tests\IntegrationTestCase;

class FeeTest extends IntegrationTestCase {
	public function setUp() {
		parent::setUp();

		$this->runSeeder('DatabaseSeeder');
		\App\Fee::truncate();
	}

	/** @test */
	public function it_gets_any_fees_from() {
		$this->authAsApi();

		$fee1 = $this->create('Fee', ['nami_id' => 2, 'title' => 'title1']);
		$fee2 = $this->create('Fee', ['nami_id' => 6, 'title' => 'title2']);

		$this->getApi('fee')
			->assertSuccess()
			->assertJson([
				['id' => $fee1->id, 'title' => 'title1', 'nami_id' => 2],
				['id' => $fee2->id, 'title' => 'title2', 'nami_id' => 6]
			]);
	}
}
