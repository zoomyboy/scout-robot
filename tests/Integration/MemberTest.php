<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Status;
use App\Payment;
use App\Member;
use Tests\IntegrationTestCase;
use App\Right;

class MemberTest extends IntegrationTestCase {
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
		$this->runMigration('ways_table');
		$this->runMigration('nationalities_table');

		$this->runSeeder('RightSeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('StatusSeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('UserSeeder');

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
		$this->withExceptionHandling();
		$this->authAsApi();
		
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
		$this->withExceptionHandling();

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

	/** @test */
	public function it_can_add_a_member_with_minimal_requirements_of_validation() {
		$this->withExceptionHandling();

		$this->authAsApi();
		auth()->user()->usergroup->rights()->attach(Right::where('key', 'member.manage')->first());

		$data = $this->postApi('member', [
			'firstname' => 'John',
			'lastname' => 'Doe',
			'birthday' => '2018-01-02',
			'joined_at' => '2017-12-07',
			'address' => 'Straße 1',
			'zip' => '45444',
			'city' => 'Köln',
			'nationality' => 4,
			'way' => 1,
			'country' => 5,
			'keepdata' => false,
			'sendnewspaper' => false
		])
			->assertSuccess();
	}

	public function validationDataProvider() {
		return [
			[['nationality' => null], ['nationality']],
			[['country' => null], ['country']],
		];
	}

	/**
	 * @test
	 * @dataProvider validationDataProvider
	 */
	public function it_should_add_validations($fields, $valid) {
		$this->withExceptionHandling();

		$this->authAsApi();
		auth()->user()->usergroup->rights()->attach(Right::where('key', 'member.manage')->first());

		$data = $this->postApi('member', array_merge([
			'firstname' => 'John',
			'lastname' => 'Doe',
			'birthday' => '2018-01-02',
			'joined_at' => '2017-12-07',
			'address' => 'Straße 1',
			'zip' => '45444',
			'city' => 'Köln',
			'nationality' => 4,
			'way' => 1,
			'country' => 5,
			'keepdata' => false,
			'sendnewspaper' => false
		], $fields))
			->assertValidationFailedWith(...$valid);
	}

	/**
	 * @test
	 * @dataProvider validationDataProvider
	 */
	public function it_should_add_validations_on_update($fields, $valid) {
		$this->withExceptionHandling();

		$this->authAsApi();
		auth()->user()->usergroup->rights()->attach(Right::where('key', 'member.manage')->first());

		$member = $this->create('Member');

		$data = $this->patchApi('member/'.$member->id, array_merge([
			'firstname' => 'John',
			'lastname' => 'Doe',
			'birthday' => '2018-01-02',
			'joined_at' => '2017-12-07',
			'address' => 'Straße 1',
			'zip' => '45444',
			'city' => 'Köln',
			'nationality' => 4,
			'way' => 1,
			'country' => 5,
			'keepdata' => false,
			'sendnewspaper' => false
		], $fields))
			->assertValidationFailedWith(...$valid);
	}
}
