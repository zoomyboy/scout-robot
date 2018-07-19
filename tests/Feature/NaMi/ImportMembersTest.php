<?php

namespace Tests\Feature\NaMi;

use App\Facades\NaMi\NaMiMember;
use App\Jobs\SyncAllNamiMembers;
use App\Member;
use Illuminate\Support\Facades\Queue;
use Tests\FeatureTestCase;

class ImportMembersTest extends FeatureTestCase {
	public $config;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('ConfSeeder');

	}

	/** @test */
	public function it_synchs_active_and_inactive_members() {
        Queue::fake();
		$this->authAsApi();

		\App\Conf::first()->update(['namiEnabled' => true]);

		$this->postApi('nami/getmembers', ['active' => true, 'inactive' => true])
			->assertSuccess();

		Queue::assertPushed(SyncAllNamiMembers::class, function($q) {
            return $q->filter['status'] == ['Aktiv', 'Inaktiv'];
        });
	}

    /** @test */
    public function it_synchs_active_members() {
        Queue::fake();
        $this->authAsApi();

        \App\Conf::first()->update(['namiEnabled' => true]);

        $this->postApi('nami/getmembers', ['active' => true, 'inactive' => false])
            ->assertSuccess();

        Queue::assertPushed(SyncAllNamiMembers::class, function($q) {
            return $q->filter['status'] == ['Aktiv'];
        });
    }

    /** @test */
    public function it_synchs_inactive_members() {
        Queue::fake();
        $this->authAsApi();

        \App\Conf::first()->update(['namiEnabled' => true]);

        $this->postApi('nami/getmembers', ['active' => false, 'inactive' => true])
            ->assertSuccess();

        Queue::assertPushed(SyncAllNamiMembers::class, function($q) {
            return $q->filter['status'] == ['Inaktiv'];
        });
    }

    /** @test */
    public function it_synchs_no_members_without_any_filter() {
        Queue::fake();
        $this->authAsApi();

        \App\Conf::first()->update(['namiEnabled' => true]);

        $this->postApi('nami/getmembers', ['active' => false, 'inactive' => false])
            ->assertSuccess();

        Queue::assertPushed(SyncAllNamiMembers::class, function($q) {
            return $q->filter['status'] == [];
        });
    }

	/** @test */
	public function it_doesnt_sync_when_nami_is_not_enabled() {
        Queue::fake();
		$this->withExceptionHandling();

		$this->authAsApi();

		\Setting::set('namiEnabled', false);

		$this->postApi('nami/getmembers', ['active' => true, 'inactive' => true])
			->assertValidationFailedWith('error');

		Queue::assertNotPushed(SyncAllNaMiMembers::class);
	}
}
