<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Status;
use App\Payment;
use App\Member;

class MemberTest extends TestCase {
	public function setUp() {
		parent::setUp();

		$this->runMigration('rights_table');
		$this->runMigration('right_usergroup_table');
		$this->runMigration('statuses_table');
		$this->runMigration('genders_table');
		$this->runMigration('countries_table');
		$this->runMigration('regions_table');
		$this->runMigration('confessions_table');
		$this->runMigration('usergroups_table');
		$this->runMigration('users_table');
		$this->runMigration('members_table');
		$this->runMigration('payments_table');

		$this->runSeeder(\StatusSeeder::class);
		$this->runSeeder(\GenderSeeder::class);
		$this->runSeeder(\CountrySeeder::class);
		$this->runSeeder(\RegionSeeder::class);
		$this->runSeeder(\ConfessionSeeder::class);
		
		$this->runSeeder(\UsergroupSeeder::class);
		$this->runSeeder(\UserSeeder::class);

		$payments = collect([
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Bezahlt')->first()->id,
				'nr' => 2011,
				'amount' => 16
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Nicht bezahlt')->first()->id,
				'nr' => 2012,
				'amount' => 18
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Rechnung versendet')->first()->id,
				'nr' => 2013,
				'amount' => 20
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Bezahlt')->first()->id,
				'nr' => 2014,
				'amount' => 22
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Nicht bezahlt')->first()->id,
				'nr' => 2015,
				'amount' => 24
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Rechnung versendet')->first()->id,
				'nr' => 2016,
				'amount' => 26
			])
		]);

		$member = factory(Member::class)->create([
			'address' => 'Stefanstr 22',
			'firstname' => 'Krug',
			'lastname' => 'Betty',
			'city' => 'Paderborn',
			'joined_at' => '2012-02-06',
			'zip' => 4875
		]);
		$member->payments()->saveMany($payments);
	}

	/** @test */
	public function it_gets_all_members_and_appends_its_strikes() {
		parent::auth('api');
		
		$this->getApi('member/table')
			->assertSuccess()
			->assertExactJson([[
				'active' => true,
				'firstname' => 'Krug',
				'lastname' => 'Betty',
				'address' => 'Stefanstr 22',
				'zip' => '4875',
				'city' => 'Paderborn',
				'joined_at' => '2012-02-06 00:00:00',
				'strikes' => '88',
				'id' => 1
			]]);
	}

	/** @test */
	public function it_gets_no_members_when_not_authed() {
		$this->getApi('member/table')
			->assertUnauthorized();
	}

	/** @test */
	public function it_gets_all_family_members() {
		$atts = [
			'firstname' => 'John',
			'lastname' => 'Doe',
			'address' => 'Str 1',
			'zip' => '67777',
			'city' => 'City'
		];

		$members = collect([
			$this->create('Member', $atts),
			$this->create('Member', array_merge($atts, ['firstname' => 'Jane'])),
			$this->create('Member', array_merge($atts, ['firstname' => 'Jane', 'zip' => '77885'])),
			$this->create('Member', array_merge($atts, ['firstname' => 'Jane', 'address' => 'Neuerst'])),
			$this->create('Member', array_merge($atts, ['firstname' => 'Jane', 'city' => 'City2']))
		]);

		$this->assertEquals($members->slice(0, 2)->toArray(), Member::family($members[0])->get()->toArray());
	}
}
