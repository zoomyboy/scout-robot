<?php

namespace App\Integration\NaMi;

use App\Member;
use App\Facades\NaMi\NaMiMember;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\StoreNaMiMember;

class StoreMembersTest extends FeatureTestCase {
	public $config;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('RightSeeder');
		$this->runSeeder('ActivitySeeder');
		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('ConfSeeder');

		Queue::fake();

		$this->authAsApi();
		
		\App\User::first()->usergroup->rights()->attach(\App\Right::where('key', 'member.manage')->first());
	}

	/** @test */
	public function it_synchs_a_nami_member_when_it_is_created() {
		\App\Conf::first()->update(['namiEnabled' => true]);
		$member = $this->make('Member', ['firstname' => 'John', 'nami_id' => null]);

		$this->postApi('member', array_merge($member->getAttributes(), [
			'way' => \App\Way::first()->id,
			'country' => \App\Way::first()->id,
			'activity' => \App\Activity::first()->id,
			'group' => \App\Group::first()->id,
			'nationality' => \App\Nationality::first()->id
		]))
			->assertSuccess();

		Queue::assertPushed(StoreNaMiMember::class, function($j) {
			return $j->member->firstname == 'John';
		});
	}

	/** @test */
	public function it_doesnt_sync_a_nami_member_when_nami_is_disabled() {
		\App\Conf::first()->update(['namiEnabled' => false]);
		$member = $this->make('Member', ['firstname' => 'John', 'nami_id' => null]);

		$this->postApi('member', array_merge($member->getAttributes(), [
			'way' => \App\Way::first()->id,
			'country' => \App\Way::first()->id,
			'activity' => \App\Activity::first()->id,
			'group' => \App\Group::first()->id,
			'nationality' => \App\Nationality::first()->id
		]))
			->assertSuccess();

		Queue::assertNotPushed(StoreNaMiMember::class, function($j) {
			return $j->member->firstname == 'John';
		});
	}

	/** @test */
	public function it_doesnt_sync_a_nami_member_when_nami_id_is_set() {
		\App\Conf::first()->update(['namiEnabled' => true]);
		$member = $this->make('Member', ['firstname' => 'John', 'nami_id' => 1355]);

		$this->postApi('member', array_merge($member->getAttributes(), [
			'way' => \App\Way::first()->id,
			'country' => \App\Way::first()->id,
			'activity' => \App\Activity::first()->id,
			'group' => \App\Group::first()->id,
			'nationality' => \App\Nationality::first()->id
		]))
			->assertSuccess();

		Queue::assertNotPushed(StoreNaMiMember::class, function($j) {
			return $j->member->firstname == 'John';
		});
	}
}
