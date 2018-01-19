<?php

namespace Tests\Integration\NaMi;

use App\Facades\NaMi\NaMiMember;
use App\Facades\NaMi\NaMi;
use Tests\IntegrationTestCase;
use Carbon\Carbon;

class StoreActivitiesTest extends IntegrationTestCase {
	public $dontFakeEvents;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('ConfSeeder');
		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('ActivitySeeder');

		$this->setUpNaMi();
	}

	/** @test */
	public function it_stores_a_members_memberships_in_nami_when_saved() {
		$this->authAsApi();

		NaMi::shouldReceive('post')
			->with('/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/1', \Mockery::any())->once()
			->andReturn((object) ['success' => true, 'responseType' => 'OK', 'data' => 716]);

		NaMi::shouldReceive('getConfig')->andReturn((object)['namiGroup' => 'test']);
		$member = $this->create('Member', []);

		$member->memberships()->create([
			'activity_id' => 8,
			'group_id' => 5
		]);
	}

	/** @test */
	public function it_stores_a_members_memberships_in_nami_and_saves_the_nami_id_in_the_model() {
		$this->authAsApi();

		// @TODO

		/* NaMi::shouldReceive('post')
			->with('/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/1', \Mockery::any())->once()
			->andReturn((object) ['success' => true, 'responseType' => 'OK', 'data' => 716]);

		NaMi::shouldReceive('getConfig')->andReturn((object)['namiGroup' => 'test']);
		$member = $this->create('Member', []);

		$member->memberships()->create([
			'activity_id' => 8,
			'group_id' => 5
		]); */
	}
}
