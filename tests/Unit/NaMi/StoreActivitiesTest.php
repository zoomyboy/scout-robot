<?php

namespace Tests\Integration\NaMi;

use Tests\UnitTestCase;
use App\Facades\NaMi\NaMiMember;
use App\Facades\NaMi\NaMi;
use App\Events\MembershipCreated;
use Illuminate\Support\Facades\Event;

class StoreMembersActivitiesTest extends UnitTestCase {
	public function setUp() {
		parent::setUp();

		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('ActivitySeeder');
	}

	/** @test */
	public function it_fires_a_store_event_when_a_membership_is_saved() {
		$this->authAsApi();

		$member = $this->create('Member', []);

		$member->memberships()->create([
			'activity_id' => 8,
			'group_id' => 5
		]);

		Event::assertDispatched(MembershipCreated::class);
	}
}
