<?php

namespace App\Integration\NaMi;

use App\Member;
use App\Facades\NaMi\NaMiMember;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\UpdateNaMiMember;

class UpdateMembersTest extends FeatureTestCase {
	public $config;

	public function setUp() {
		parent::setUp();

        $this->runSeeder('CountrySeeder');
        $this->runSeeder('ActivitySeeder');
        $this->runSeeder('GenderSeeder');
        $this->runSeeder('WaySeeder');
        $this->runSeeder('NationalitySeeder');
        $this->runSeeder('ConfSeeder');
        $this->runSeeder('UsergroupSeeder');
        $this->runSeeder('ConfessionSeeder');
        $this->runSeeder('RegionSeeder');

		Queue::fake();

		$this->authAsApi();
		
		\App\User::first()->usergroup->rights()->attach(\App\Right::where('key', 'member.manage')->first());
	}

	/** @test */
	public function it_synchs_a_nami_member_when_it_is_updated() {
        $this->withExceptionHandling();

		\App\Conf::first()->update(['namiEnabled' => true]);
		$member = $this->create('Member', ['firstname' => 'John', 'nami_id' => 3362]);

		$data = $this->patchApi("member/$member->id", array_merge($member->getAttributes(), [
			'way' => \App\Way::first()->id,
			'country' => \App\Way::first()->id,
			'activity' => \App\Activity::where('nami_id', 35)->first()->id,
			'group' => \App\Group::where('id', 2)->first()->id,
			'nationality' => \App\Nationality::first()->id,
            'subscription' => null,
            'firstname' => 'Max'
        ]))
		    ->assertSuccess();

		Queue::assertPushed(UpdateNaMiMember::class, function($j) {
			return $j->member->firstname == 'Max' && $j->oldmember['firstname'] == 'John';
		});
	}

	/** @test */
	public function it_doesnt_update_a_nami_member_when_nami_is_disabled() {
		\App\Conf::first()->update(['namiEnabled' => false]);
		$member = $this->create('Member', ['firstname' => 'John', 'nami_id' => 3362]);

		$this->patchApi("member/$member->id", array_merge($member->getAttributes(), [
			'way' => \App\Way::first()->id,
			'country' => \App\Way::first()->id,
			'activity' => \App\Activity::where('nami_id', 35)->first()->id,
			'group' => \App\Group::where('id', 2)->first()->id,
			'nationality' => \App\Nationality::first()->id,
			'subscription' => null
		]))
			->assertSuccess();

		Queue::assertNotPushed(UpdateNaMiMember::class);
	}

	/** @test */
	public function it_doesnt_update_a_nami_member_when_nami_id_is_not_set() {
		\App\Conf::first()->update(['namiEnabled' => true]);
		$member = $this->create('Member', ['firstname' => 'John', 'nami_id' => null]);

		$this->patchApi("member/$member->id", array_merge($member->getAttributes(), [
			'way' => \App\Way::first()->id,
			'country' => \App\Way::first()->id,
			'activity' => \App\Activity::where('nami_id', 35)->first()->id,
			'group' => \App\Group::where('id', 2)->first()->id,
			'nationality' => \App\Nationality::first()->id,
			'subscription' => null
		]))
			->assertSuccess();

		Queue::assertNotPushed(UpdateNaMiMember::class);
	}
}
