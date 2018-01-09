<?php

namespace Tests\Integration\Mass;

use Tests\IntegrationTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Notifications\EmailBillNotification;
use Illuminate\Support\Facades\Notification;
use \App\Member;
use App\Status;
use App\Payment;

class MassMailTest extends IntegrationTestCase {

	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('StatusSeeder');
	}

	/** @test */
	public function it_doesnt_notify_anyone_when_user_is_logged_out() {
		$this->withExceptionHandling();

		$this->postApi('mass/email/bill', [
			'deadline' => '02-02-2018',
			'includeFamilies' => true,
			'wayEmail' => true,
			'wayPost' => true,
		])
			->assertUnauthorized();
	}

	/** @test */
	public function it_has_to_enter_ways() {
		$this->withExceptionHandling();

		$this->authAsApi();

		$this->postApi('mass/email/bill', [
			'deadline' => '2018-02-02'
		])
			->assertValidationFailedWith('includeFamilies', 'wayPost', 'wayEmail');

		$this->postApi('mass/email/bill', [
			'deadline' => '02-02-2018',
			'includeFamilies' => '',
			'wayEmail' => '',
			'wayPost' => 'dd',
			'updatePayments' => '',
		])
			->assertValidationFailedWith('includeFamilies', 'wayPost', 'wayEmail', 'updatePayments');
	}

	public function senderProvider() {
		return [
			[
				[
					[['way_id' => '1'], true, [0]],
					[['way_id' => '2'], true, [1]],
				],
				'families' => false, 'email' => true, 'post' => true
			],
			[
				[
					[['way_id' => '1'], false],
					[['way_id' => '2'], true, [1]],
				],
				'families' => false, 'email' => false, 'post' => true
			],
			[
				[
					[['way_id' => '1'], true, [0]],
					[['way_id' => '2'], false],
				],
				'families' => false, 'email' => true, 'post' => false
			],
			[
				[
					[['way_id' => '1'], true, [0]],
					[['way_id' => '2'], false],
				],
				'families' => false, 'email' => true, 'post' => false
			],
			[
				[
					[['firstname' => 'John', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '1'], true, [0]],
					[['firstname' => 'Jane', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '2'], true, [1]],
				],
				'families' => false, 'email' => true, 'post' => true
			],
			[
				[
					[['firstname' => 'John', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '1'], true, [0]],
					[['firstname' => 'Jane', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '2'], false],
				],
				'families' => false, 'email' => true, 'post' => false
			],
			[
				[
					[['firstname' => 'John', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '1'], false],
					[['firstname' => 'Jane', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '2'], true, [1]],
				],
				'families' => false, 'email' => false, 'post' => true
			],

			// With Families
			[
				[
					[['lastname' => 'Doe1', 'way_id' => '1'], true, [0]],
					[['lastname' => 'Doe2', 'way_id' => '2'], true, [1]],
				],
				'families' => true, 'email' => true, 'post' => true,
			],
			[
				[
					[['lastname' => 'Doe1', 'way_id' => '1'], false],
					[['lastname' => 'Doe2', 'way_id' => '2'], true, [1]],
				],
				'families' => true, 'email' => false, 'post' => true,
			],
			[
				[
					[['lastname' => 'Doe1', 'way_id' => '1'], true, [0]],
					[['lastname' => 'Doe2', 'way_id' => '2'], false],
				],
				'families' => true, 'email' => true, 'post' => false,
			],
			[
				[
					[['firstname' => 'John', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '1', 'email' => 'john1@example.com'], true, [0,1]],
					[['firstname' => 'Jane', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '2', 'email' => 'john2@example.com'], true, [0,1]],
				],
				'families' => true, 'email' => true, 'post' => true,
			],
			[
				[
					[['firstname' => 'John', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '1', 'email' => 'john1@example.com'], true, [0,1]],
					[['firstname' => 'Jane', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '2', 'email' => 'john1@example.com'], false],
				],
				'families' => true, 'email' => true, 'post' => true,
			],
			[
				[
					[['firstname' => 'John', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '1', 'email' => 'john1@example.com'], true, [0]],
					[['firstname' => 'Jane', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG2', 'address' => 'Str 2', 'way_id' => '2', 'email' => 'john1@example.com'], true, [1]],
				],
				'families' => true, 'email' => true, 'post' => true,
			],
			[
				[
					[['firstname' => 'John', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '1'], false],
					[['firstname' => 'Jane', 'lastname' => 'Doe', 'zip' => '12345', 'city' => 'SG', 'address' => 'Str 2', 'way_id' => '2'], true, [1]],
				],
				'families' => true, 'email' => false, 'post' => true,
			],
		];
	}

	/**
	 * @test
	 * @dataProvider senderProvider
	 */
	public function it_sends_notifications($members, $families, $email, $post) {
		$this->authAsApi();

		$members = array_map(function($member) {
			$myMember = $this->create('Member', $member[0]);
			$payment = new Payment(['amount' => '1000', 'nr' => '2015']);
			$payment->status()->associate(Status::find(1));
			$payment->member()->associate($myMember);
			$payment->save();

			$member[0] = $myMember;

			return $member;
		}, $members);

		$this->postApi('mass/email/bill', [
			'deadline' => '2018-02-02',
			'includeFamilies' => $families,
			'wayEmail' => $email,
			'wayPost' => $post,
			'updatePayments' => true
		])
			->assertSuccess();

		foreach($members as $ind => $member) {
			if ($member[1] == true) {
				$expectedMembers = collect($members)
					->only($member[2])
					->map(function($em) {
						return $em[0];
					})
					->pluck('id')->toArray();

				Notification::assertSentTo($member[0], EmailBillNotification::class, function($m) use ($member, $families, $expectedMembers) {
					return $m->member->id == $member[0]->id
						&& $m->family == $families
						&& $m->deadline == '2018-02-02'
						&& $expectedMembers == $m->members->pluck('id')->toArray();
				});
			} else {
				Notification::assertNotSentTo($member[0], EmailBillNotification::class, function($m) use ($member, $families) {
					return $m->member->id == $member[0]->id
						&& $m->family == $families
						&& $m->deadline == '2018-02-02';
				});
			}
		}
	}

	/**
	 * @test
	 * @dataProvider senderProvider
	 */
	public function it_doesnt_send_notifications_when_no_payments($members, $families, $email, $post) {
		$this->authAsApi();

		$members = array_map(function($member) {
			return [$this->create('Member', $member[0]), $member[1]];
		}, $members);

		$this->postApi('mass/email/bill', [
			'deadline' => '2018-02-02',
			'includeFamilies' => $families,
			'wayEmail' => $email,
			'wayPost' => $post,
			'updatePayments' => true
		])
			->assertSuccess();

		foreach($members as $ind => $member) {
			Notification::assertNotSentTo($member[0], EmailBillNotification::class);
		}
	}

	/**
	 * @test
	 * @dataProvider senderProvider
	 */
	public function it_doesnt_send_notifications_when_only_paid_payments($members, $families, $email, $post) {
		$this->authAsApi();

		$members = array_map(function($member) {
			$myMember = $this->create('Member', $member[0]);
			$payment = new Payment(['amount' => '1000', 'nr' => '2015']);
			$payment->status()->associate(Status::find(3));
			$payment->member()->associate($myMember);
			$payment->save();

			return [$this->create('Member', $member[0]), $member[1]];
		}, $members);

		$this->postApi('mass/email/bill', [
			'deadline' => '2018-02-02',
			'includeFamilies' => $families,
			'wayEmail' => $email,
			'wayPost' => $post,
			'updatePayments' => true
		])
			->assertSuccess();

		foreach($members as $ind => $member) {
			Notification::assertNotSentTo($member[0], EmailBillNotification::class);
		}
	}

	/**
	 * @test
	 * @dataProvider senderProvider
	 */
	public function it_doesnt_send_notifications_when_only_received_payments($members, $families, $email, $post) {
		$this->authAsApi();

		$members = array_map(function($member) {
			$myMember = $this->create('Member', $member[0]);
			$payment = new Payment(['amount' => '1000', 'nr' => '2015']);
			$payment->status()->associate(Status::find(2));
			$payment->member()->associate($myMember);
			$payment->save();

			return [$this->create('Member', $member[0]), $member[1]];
		}, $members);

		$this->postApi('mass/email/bill', [
			'deadline' => '2018-02-02',
			'includeFamilies' => $families,
			'wayEmail' => $email,
			'wayPost' => $post,
			'updatePayments' => true
		])
			->assertSuccess();

		foreach($members as $ind => $member) {
			Notification::assertNotSentTo($member[0], EmailBillNotification::class);
		}
	}

}
