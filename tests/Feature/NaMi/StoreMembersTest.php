<?php

use App\Facades\NaMi\NaMiMember;
use App\Conf;
use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\SetsUpNaMi;

class StoreMembersTest extends FeatureTestCase {

	use DatabaseMigrations;
	use SetsUpNaMi;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('ConfSeeder');

		$this->setUpNaMi();
	}

	/** @test */
	public function it_stores_a_member_in_nami_when_saved_for_the_first_time() {
		$this->authAsApi();

		$member = $this->make('Member', ['nami_id' => null]);

		$member->save();

		$member->fresh();

		$this->assertNotNull($member->nami_id);

		$this->assertEquals($member->nami_id, NaMiMember::single($member->nami_id)->id);
	}
}

