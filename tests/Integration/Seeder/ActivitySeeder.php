<?php

use Tests\IntegrationTestCase;

class ActivitySederTest extends IntegrationTestCase  {
	public function setUp() {
		parent::setUp();
	}

	/** @test */
	public function it_seeds_all_activities() {
		$this->runSeeder('ActivitySeeder');

		$this->assertTrue(\App\Activity::first()->groups->first()->is_group);
		$this->assertEquals(1, \App\Group::where('title', 'Biber')->first()->group_order);
	}
}
