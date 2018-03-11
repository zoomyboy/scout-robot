<?php

namespace Tests\Feature\Mass;

use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use \App\Member;
use App\Conf;
use App\Subscription;
use App\Payment;
use App\Status;

class RememberTest extends FeatureTestCase {

	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('FeeSeeder');
		$this->runSeeder('SubscriptionSeeder');
		$this->runSeeder('NationalitySeeder');
		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('ConfSeeder');
		$this->runSeeder('StatusSeeder');

		Conf::first()->update(['groupname' => 'Bla']);
		$this->subject = 'Zahlungserinnerung von Bla';

		$this->clearMailtrap();
	}

	public function senderProvider() {
		return [
			[
				[
					['way_id' => '1', 'lastname' => 'Doe', 'firstname' => 'John', 'email' => 'john@doe.de'],
					['way_id' => '1', 'lastname' => 'Doe', 'firstname' => 'Jane', 'email' => 'jane@doe.de']
				],
				[
					['pdf' => 'Zahlungserinnerung für John Doe.pdf', 'to' => 'john@doe.de'],
					['pdf' => 'Zahlungserinnerung für Jane Doe.pdf', 'to' => 'jane@doe.de'],
				],
				'families' => false, 'email' => true, 'post' => true,
			],
			[
				[
					['way_id' => '1', 'lastname' => 'Doe', 'firstname' => 'John', 'email' => 'john@doe.de'],
					['way_id' => '1', 'lastname' => 'Doe', 'firstname' => 'Jane', 'email' => 'jane@doe.de']
				],
				[
					['pdf' => 'Zahlungserinnerung für Familie Doe.pdf', 'to' => 'john@doe.de'],
					['pdf' => 'Zahlungserinnerung für Familie Doe.pdf', 'to' => 'jane@doe.de'],
				],
				'families' => true, 'email' => true, 'post' => true,
			],
			[
				[
					['way_id' => '1', 'lastname' => 'Doe', 'firstname' => 'John', 'email' => 'john@doe.de'],
					['way_id' => '2', 'lastname' => 'Doe', 'firstname' => 'Jane', 'email' => 'jane@doe.de']
				],
				[
					['pdf' => 'Zahlungserinnerung für Familie Doe.pdf', 'to' => 'jane@doe.de'],
				],
				'families' => true, 'email' => false, 'post' => true,
			],
			[
				[
					['way_id' => '1', 'lastname' => 'Doe', 'firstname' => 'John', 'email' => 'john@doe.de'],
					['way_id' => '2', 'lastname' => 'Doe', 'firstname' => 'Jane', 'email' => 'jane@doe.de']
				],
				[
					['pdf' => 'Zahlungserinnerung für Familie Doe.pdf', 'to' => 'john@doe.de'],
				],
				'families' => true, 'email' => true, 'post' => false,
			],
		];
	}

	/**
	 * @test
	 * @dataProvider senderProvider
	 */
	public function it_sends_notifications($members, $emails, $family, $email, $post) {
		$this->authAsApi();

		$members = array_map(function($member) {
            $myMember = $this->create(
                'Member',
                array_merge($member, ['email_parents' => null])
            );
			$payment = new Payment(['amount' => '1000', 'nr' => '2015']);
			$payment->status()->associate(Status::find(2));
			$payment->subscription()->associate(Subscription::find(3));
			$payment->member()->associate($myMember);
			$payment->save();

			return $this->create('Member', $member);
		}, $members);

		$this->postApi('mass/email/remember', [
			'deadline' => '02-02-2018',
			'includeFamilies' => $family,
			'wayEmail' => $email,
			'wayPost' => $post,
			'updatePayments' => true
		])
			->assertSuccess();

		foreach($emails as $email) {
			$this->assertMailtrap()
				->to($email['to'])
				->withAttachment($email['pdf'])
				->withSubject($this->subject)
				->wasSent();
		}

		$this->assertMailtrapCount(count($emails));
	}
}
