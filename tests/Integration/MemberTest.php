<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Status;
use App\Payment;
use App\Member;
use App\Activity;
use App\Group;
use Tests\IntegrationTestCase;
use App\Right;
use Illuminate\Support\Facades\Queue;

class MemberTest extends IntegrationTestCase {
	public function setUp() {
		parent::setUp();

		$this->runSeeder('RightSeeder');
		$this->runSeeder('ActivitySeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('StatusSeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('UserSeeder');
		$this->runSeeder('FeeSeeder');
		$this->runSeeder('ConfSeeder');

		$subscriptions = collect([
			$this->create('Subscription', ['amount' => '2000', 'fee_id' => 1, 'title' => 'Sub1']),
			$this->create('Subscription', ['amount' => '3000', 'fee_id' => 2, 'title' => 'Sub2']),
			$this->create('Subscription', ['amount' => '4000', 'fee_id' => 3, 'title' => 'Sub3'])
		]);

		$payments = collect([
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Bezahlt')->first()->id,
				'nr' => 2011,
				'subscription_id' => 2
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Nicht bezahlt')->first()->id,
				'nr' => 2012,
				'subscription_id' => 3
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Rechnung versendet')->first()->id,
				'nr' => 2013,
				'subscription_id' => 1
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Bezahlt')->first()->id,
				'nr' => 2014,
				'subscription_id' => 1
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Nicht bezahlt')->first()->id,
				'nr' => 2015,
				'subscription_id' => 2
			]),
			factory(Payment::class)->make([
				'status_id' => Status::whereTitle('Rechnung versendet')->first()->id,
				'nr' => 2016,
				'subscription_id' => 3
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

		Queue::fake();
	}

	/** @test */
	public function it_gets_all_members_and_appends_its_strikes() {
		$this->withExceptionHandling();
		$this->authAsApi();
		
		$this->getApi('member/table')
			->assertSuccess()
			->assertJson([[
				'active' => true,
				'firstname' => 'Krug',
				'lastname' => 'Betty',
				'address' => 'Stefanstr 22',
				'zip' => '4875',
				'city' => 'Paderborn',
				'joined_at' => '2012-02-06 00:00:00',
				'strikes' => '13000',
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

		$this->assertEquals($members->slice(0, 2)->pluck('id')->toArray(), Member::family($members[0])->get()->pluck('id')->toArray());
	}

	public function succeedsDataProvider() {
		return [ 
			[
				[],
				['activity' => 35, 'group' => 1],
				['activity' => 35, 'gro,up' => 1],
			    ['email' => '', 'email_parents' => '', 'way' => 2]
			]
		];
	}

	/**
	 * @test
	 * @dataProvider succeedsDataProvider
	 */
	public function it_can_add_a_member_with_minimal_requirements_of_validation($fields) {
		//$this->withExceptionHandling();

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
			'sendnewspaper' => false,
			'activity' => 17,	//Schnupperer
			'group' => 1,
		], $fields))
			->assertSuccess();
	}

	public function validationStoreDataProvider() {
		return [
			'one' => [['nationality' => null], ['nationality']],
			'two' => [['country' => null], ['country']],
			'ActivityIsMissing' => [['activity' => null], ['activity']],
			'ActivityIsMissingInDb' => [['activity' => 35, 'group' => 1], ['activity']],

			// Mitglied
			'GroupIsMissing' => [['activity' => 1, 'group' => null], ['group']],
			'groupIsNotFromGivenActivity' => [['activity' => 1, 'group' => 6], ['group']],
			'groupIsNotFound' => [['activity' => 1, 'group' => 116], ['group']],

			'noPaymentGiven' => [['activity' => 8, 'group' => 1, 'subscription' => null], ['subscription']],

			// Leiter
			'GroupIsMissing2' => [['activity' => 8, 'group' => null], ['group']],
			'groupIsNotFromGivenActivity2' => [['activity' => 8, 'group' => 6], ['group']],
			'groupIsNotFound2' => [['activity' => 8, 'group' => 116], ['group']],
			'groupHasNoValidSubscription' => [['subscription' => null, 'activity' => 8, 'group' => 5], ['subscription']],
			'groupHasNoValidSubscription2' => [['subscription' => null, 'activity' => 8, 'group' => null], ['subscription']],

			//Schnupperer
			'GroupIsMissing3' => [['activity' => 17, 'group' => null], ['group']],
			'groupIsNotFound3' => [['activity' => 17, 'group' => 116], ['group']],
		];
	}

	public function validationUpdateDataProvider() {
		return [
			'one' => [['nationality' => null], ['nationality']],
			'two' => [['country' => null], ['country']],
			'noEmailsButEmailWaySet' => [['email' => '', 'email_parents' => '', 'way' => 1], ['way', 'email', 'email_parents']],
		];
	}

	/**
	 * @test
	 * @dataProvider validationStoreDataProvider
	 */
	public function it_should_add_validations_on_store($fields, $valid) {
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
			'sendnewspaper' => false,
			'activity' => 17,	//Schnupperer
			'group' => 1,
		], $fields))
		->assertValidationFailedWith(...$valid);
	}

	/**
	 * @test
	 * @dataProvider validationUpdateDataProvider
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
			'sendnewspaper' => false,
			'activity' => 1,
			'group' => 1
		], $fields))
			->assertValidationFailedWith(...$valid);
	}
}
