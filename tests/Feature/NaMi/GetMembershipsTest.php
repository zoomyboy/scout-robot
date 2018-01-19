<?php

namespace App\Feature\NaMi;

use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\NaMi\NaMiMembership;
use App\Conf;
use App\Exceptions\NaMi\LoginException;
use Tests\Traits\SetsUpNaMi;

class GetMembershipsTest extends FeatureTestCase {

	use DatabaseMigrations;
	use SetsUpNaMi;

	public function setUp() {
		parent::setUp();

		@unlink(config('nami.cookie'));

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfSeeder');

		$this->setUpNaMi();
	}

	/** @test */
	public function it_receives_all_memberships_from_nami() {
		$list = NaMiMembership::all(89418);
		$this->assertCount(7, $list);
	}

	/** @test */
	public function it_receives_a_single_membership_from_nami() {
		$list = NaMiMembership::single(89418, 415177);
		$this->assertEquals('Rover', $list->untergliederung);
	}
}
