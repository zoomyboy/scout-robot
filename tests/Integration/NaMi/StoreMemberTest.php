<?php

use App\Facades\NaMi\NaMiMember;
use Tests\IntegrationTestCase;
use App\Conf;

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
	}

	/** @test */
	public function it_stores_a_member_in_nami_when_saved_for_the_first_time() {
		$this->authAsApi();

		$member = $this->make('Member', ['nami_id' => null]);
		Conf::first()->update(['namiEnabled' => true]);

		NaMiMember::shouldReceive('store')
			->with($member)
			->once();

		$member->save();
	}

	/** @test */
	public function it_doesnt_store_a_member_in_nami_when_nami_id_is_set() {
		$this->authAsApi();

		$member = $this->make('Member', ['nami_id' => 56666]);
		Conf::first()->update(['namiEnabled' => true]);

		NaMiMember::shouldReceive('store')
			->with($member)
			->never();

		$member->save();
	}

	/** @test */
	public function it_doesnt_store_a_member_in_nami_when_no_nami_connection_is_set() {
		$this->authAsApi();

		Conf::first()->update(['namiEnabled' => false]);

		$member = $this->make('Member', ['nami_id' => null]);

		NaMiMember::shouldReceive('store')
			->with($member)
			->never();

		$member->save();
	}
}
