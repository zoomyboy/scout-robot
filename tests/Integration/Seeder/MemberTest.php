<?php

namespace Tests\Integration\Seeder;

use Tests\TestCase;
use \App\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberTest extends TestCase
{
	public function setUp() {
		parent::setUp();

		Config::set('seed.default_usergroup', 'SA');
		Config::set('seed.default_username', 'Admin');
		Config::set('seed.default_userpw', 'admin22');
		Config::set('seed.default_usermail', 'admin@example.tz');

		$this->runMigration('genders_table');
		$this->runMigration('countries_table');
		$this->runMigration('regions_table');
		$this->runMigration('confessions_table');
		$this->runMigration('members_table');
		$this->runMigration('ways_table');
		$this->runMigration('nationalities_table');

		$this->runSeeder(\NationalitySeeder::class);
		$this->runSeeder(\GenderSeeder::class);
		$this->runSeeder(\RegionSeeder::class);
		$this->runSeeder(\ConfessionSeeder::class);
		$this->runSeeder(\CountrySeeder::class);
		$this->runSeeder(\WaySeeder::class);
		$this->runSeeder(\MemberSeeder::class);
	}

	/** @test */
	public function it_seeds_a_member_with_properties() {
		$member = Member::latest()->first();
		$this->assertNotNull($member);
		$this->assertNotEmpty($member->firstname);
		$this->assertNotEmpty($member->other_country);
		$this->assertNotNull(\App\Country::find($member->country->id));
		$this->assertNotNull(\App\Gender::find($member->gender->id));
		$this->assertNotNull(\App\Region::find($member->region->id));
		$this->assertNotNull(\App\Confession::find($member->confession->id));
		$this->assertNotNull(\App\Way::find($member->way->id));
		$this->assertDate($member->birthday);
		$this->assertDateBefore(Carbon::now(), $member->birthday);
		$this->assertDate($member->joined_at);
		$this->assertDateBefore(Carbon::now(), $member->joined_at);
		$this->assertInternalType('boolean', $member->keepdata);
		$this->assertInternalType('boolean', $member->sendnewspaper);
		$this->assertNotEmpty($member->address);
		$this->assertNotEmpty($member->further_address);
		$this->assertNotEmpty($member->zip);
		$this->assertNotEmpty($member->city);
		$this->assertNotEmpty($member->nickname);
		$this->assertPhone($member->phone);
		$this->assertPhone($member->mobile);
		$this->assertPhone($member->fax);
		$this->assertPhone($member->business_phone);
		$this->assertEmail($member->email);
		$this->assertEmail($member->email_parents);
	}
}
