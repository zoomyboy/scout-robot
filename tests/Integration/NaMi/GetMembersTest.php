<?php

namespace App\Integration\NaMi;

use App\Member;
use App\Facades\NaMi\NaMi;
use Tests\IntegrationTestCase;
use App\Jobs\SyncAllNaMiMembers;

class GetMembersTest extends IntegrationTestCase {
	public $config;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('ConfSeeder');
		$this->runSeeder('GenderSeeder');

		$this->setUpNaMi();
	}

	/** @test */
	public function it_saves_a_members_firstname() {
		$this->authAsApi();

		NaMi::createMember(['vorname' => 'Julia', 'id' => 2334]);

		SyncAllNaMiMembers::dispatch();	

		$member = Member::where('nami_id', 2334)->first();
		$this->assertNotNull($member);
		$this->assertEquals('Julia', $member->firstname);
	}

	/** @test */
	public function it_saves_a_members_lastname() {
		$this->authAsApi();

		NaMi::createMember(['nachname' => 'Heut', 'id' => 2334]);

		SyncAllNaMiMembers::dispatch();	

		$member = Member::where('nami_id', 2334)->first();
		$this->assertNotNull($member);
		$this->assertEquals('Heut', $member->lastname);
	}

	/** @test */
	public function it_saves_a_members_birthday() {
		$this->authAsApi();

		NaMi::createMember(['geburtsDatum' => '2016-04-05 00:00:00', 'id' => 2334]);

		SyncAllNaMiMembers::dispatch();	

		$member = Member::where('nami_id', 2334)->first();
		$this->assertNotNull($member);
		$this->assertEquals('2016-04-05', $member->birthday->format('Y-m-d'));
	}

	/** @test */
	public function it_saves_a_members_gender() {
		$this->authAsApi();

		NaMi::createMember(['geschlechtId' => 19, 'id' => 2334]);

		SyncAllNaMiMembers::dispatch();	

		$member = Member::where('nami_id', 2334)->first();
		$this->assertNotNull($member);
		$this->assertEquals('Männlich', $member->gender->title);
	}

	/** @test */
	public function it_sets_a_members_gender_to_null_when_gender_is_keine_angabe() {
		$this->authAsApi();

		NaMi::createMember(['geschlechtId' => 23, 'id' => 2334]);

		SyncAllNaMiMembers::dispatch();	

		$member = Member::where('nami_id', 2334)->first();
		$this->assertNotNull($member);
		$this->assertNull($member->gender);
	}

	/** @test */
	public function it_overwrites_a_members_firstname_that_is_found_locally_by_nami_id() {
		NaMi::createMember(['vorname' => 'Hans', 'id' => 2334]);

		SyncAllNaMiMembers::dispatch();	

		$this->assertNotNull(Member::where('firstname', 'Hans')->first());
		$this->assertCount(1, Member::get());

		NaMi::updateMember(2334, ['vorname' => 'Peter']);

		SyncAllNaMiMembers::dispatch();	

		$this->assertCount(1, Member::get());
		$this->assertNotNull(Member::where('firstname', 'Peter')->first());
	}
}
