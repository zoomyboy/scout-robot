<?php

namespace Tests\Integration\Mass;

use Tests\IntegrationTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Notifications\EmailBillNotification;
use Illuminate\Support\Facades\Notification;
use App\Member;

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

	/** @test */
	public function it_notifies_a_user_when_mass_email_sent() {
		$this->authAsApi();

		$this->create('Member');
		$this->create('Member');
		$this->create('Member');

		$this->postApi('mass/email/bill', [
			'deadline' => '02-02-2018',
			'includeFamilies' => false,
			'wayEmail' => true,
			'wayPost' => true,
			'updatePayments' => true
		])
			->assertSuccess();


		Notification::assertSentTo(Member::get(), EmailBillNotification::class);
	}

	/** @test */
	public function it_notifies_only_the_users_with_post_ways_when_requested() {
		$this->authAsApi();

		$member1 = $this->create('Member', ['way_id' => 1]);
		$member2 = $this->create('Member', ['way_id' => 2]);

		$this->postApi('mass/email/bill', [
			'deadline' => '02-02-2018',
			'includeFamilies' => false,
			'wayEmail' => true,
			'wayPost' => false,
			'updatePayments' => true
		])
			->assertSuccess();

		Notification::assertSentTo($member1, EmailBillNotification::class);
		Notification::assertNotSentTo($member2, EmailBillNotification::class);
	}

	/** @test */
	public function it_notifies_only_the_users_with_email_ways_when_requested() {
		$this->authAsApi();

		$member1 = $this->create('Member', ['way_id' => 1]);
		$member2 = $this->create('Member', ['way_id' => 2]);

		$this->postApi('mass/email/bill', [
			'deadline' => '02-02-2018',
			'includeFamilies' => false,
			'wayEmail' => false,
			'wayPost' => true,
			'updatePayments' => true
		])
			->assertSuccess();

		Notification::assertSentTo($member2, EmailBillNotification::class);
		Notification::assertNotSentTo($member1, EmailBillNotification::class);
	}
}
