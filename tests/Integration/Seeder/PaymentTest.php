<?php

namespace Tests\Integration\Seeder;

use \App\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Tests\IntegrationTestCase;

class PaymentTest extends IntegrationTestCase
{
	public function setUp() {
		parent::setUp();

		Config::set('seed.default_usergroup', 'SA');
		Config::set('seed.default_username', 'Admin');
		Config::set('seed.default_userpw', 'admin22');
		Config::set('seed.default_usermail', 'admin@example.tz');

		$this->runSeeder(\FeeSeeder::class);
		$this->runSeeder(\SubscriptionSeeder::class);
		$this->runSeeder(\GenderSeeder::class);
		$this->runSeeder(\NationalitySeeder::class);
		$this->runSeeder(\RegionSeeder::class);
		$this->runSeeder(\ConfessionSeeder::class);
		$this->runSeeder(\CountrySeeder::class);
		$this->runSeeder(\StatusSeeder::class);
		$this->runSeeder('WaySeeder');
		$this->runSeeder(\MemberSeeder::class);
		$this->runSeeder(\PaymentSeeder::class);

	}

	/** @test */
	public function it_seeds_payments_for_all_members() {
		$member = Member::latest()->first();
		$this->assertNotNull($member->payments);

		$this->assertGreaterThanOrEqual(0, $member->payments->count());
		$this->assertLessThanOrEqual(5, $member->payments->count());

		foreach($member->payments as $payment) {
			$this->assertInternalType('integer', $payment->subscription->amount);
			$this->assertGreaterThanOrEqual(1, $payment->subscription->amount);
			$this->assertInstanceOf(\App\Status::class, $payment->status);	
			$this->assertRegExp('/[0-9]{4}/', $payment->nr);
		}
	}
}
