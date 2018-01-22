<?php

use App\Facades\NaMi\NaMiMember;
use Tests\IntegrationTestCase;
use App\Jobs\StoreNaMiMember;
use App\Conf;
use App\Member;
use App\Activity;
use App\Group;
use App\Facades\NaMi\NaMiMembership;
use Carbon\Carbon;

class StoreMemberTest extends IntegrationTestCase {
	public $dontFakeEvents = true;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('ActivitySeeder');
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
	public function it_stores_a_members_membership_for_the_first_time() {
		Conf::first()->update(['namiEnabled' => true]);

		$member = $this->make('Member', ['nami_id' => null, 'firstname' => 'John']);
		$member->save();

		$member->memberships()->create([
			'activity_id' => Activity::where('nami_id', 6)->first()->id,
			'group_id' => Group::where('nami_id', 4)->first()->id
		]);

		StoreNaMiMember::dispatch($member);

		$namiId = Member::first()->nami_id;
		$this->assertNotNull($namiId);

		$member = Member::where('nami_id', $namiId)->first();
		$this->assertNotNull($member);
		$this->assertEquals('John', $member->firstname);

		$memberships = NaMiMembership::all($namiId);
		$this->assertCount(1, $memberships);

		$singleMembership = NaMiMembership::single($namiId, $memberships[0]->id);
		$this->assertEquals($singleMembership->id, $member->memberships()->first()->nami_id);
		$this->assertEquals(6, $singleMembership->taetigkeitId);
		$this->assertEquals(4, $singleMembership->untergliederungId);
		$this->assertEquals(Carbon::now()->format('Y-m-d'), Carbon::parse($singleMembership->aktivVon)->format('Y-m-d'));
		$this->assertNull($singleMembership->aktivBis);
	}
}
