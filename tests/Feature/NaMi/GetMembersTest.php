<?php

namespace App\Integration\NaMi;

use App\Member;
use App\Facades\NaMi\NaMiMember;
use Tests\FeatureTestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SyncAllNaMiMembers;

class ImportMembersTest extends FeatureTestCase {
	public $config;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('ConfSeeder');

		Queue::fake();
	}

	/** @test */
	public function it_synchs_all_nami_members_when_nami_is_enabled() {
		$this->authAsApi();

		\App\Conf::first()->update(['namiEnabled' => true]);

		$this->postApi('nami/getmembers', ['active' => true, 'inactive' => true])
			->assertSuccess();

		Queue::assertPushed(SyncAllNaMiMembers::class);
	}

	/** @test */
	public function it_synchs_only_active_members() {
		$this->authAsApi();

		\App\Conf::first()->update(['namiEnabled' => true]);

		$this->postApi('nami/getmembers', ['active' => true, 'inactive' => false])
			->assertSuccess();

        Queue::assertPushed(SyncAllNaMiMembers::class, function($q) {
            return $q->filter == ['status' => 'Aktiv'];       
        });
	}


	/** @test */
	public function it_synchs_only_inactive_members() {
		$this->authAsApi();

		\App\Conf::first()->update(['namiEnabled' => true]);

		$this->postApi('nami/getmembers', ['inactive' => true, 'active' => true])
			->assertSuccess();

        Queue::assertPushed(SyncAllNaMiMembers::class, function($q) {
            return $q->filter == ['status' => 'Aktiv|Inaktiv'];       
        });
	}

	/** @test */
	public function it_doesnt_sync_when_nami_is_not_enabled() {
		$this->withExceptionHandling();

		$this->authAsApi();

		\App\Conf::first()->update(['namiEnabled' => false]);

		$this->postApi('nami/getmembers', ['active' => true, 'inactive' => true])
			->assertValidationFailedWith('error');

		Queue::assertNotPushed(SyncAllNaMiMembers::class);
	}
}
