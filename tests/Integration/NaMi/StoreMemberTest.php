<?php

use App\Facades\NaMi\NaMiMember;
use Tests\IntegrationTestCase;
use App\Jobs\StoreNaMiMember;
use App\Conf;
use App\Member;

class StoreMemberTest extends IntegrationTestCase {
	public $dontFakeEvents = true;

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
		$member = $this->make('Member', ['nami_id' => null, 'firstname' => 'John']);
		Conf::first()->update(['namiEnabled' => true]);
		$member->save();

		StoreNaMiMember::dispatch($member);

		$namiId = Member::first()->nami_id;
		$this->assertNotNull($namiId);

		$member = Member::where('nami_id', $namiId)->first();
		$this->assertNotNull($member);

		$this->assertEquals('John', $member->firstname);
	}
}
