<?php

namespace Tests\Feature\NaMi;

use Setting;
use App\Member;
use Tests\FeatureTestCase;
use App\Facades\NaMi\NaMiMember;
use App\Jobs\SyncAllNamiMembers;
use App\Events\Import\MemberCreated;
use Illuminate\Support\Facades\Queue;

class PushGetmembersJobToQueueTest extends FeatureTestCase {
	public $config;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('UsergroupSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('ConfSeeder');

        $this->authAsApi();
	}

	/** @test */
	public function it_synchs_active_and_inactive_members() {
		Setting::set('namiEnabled', true);

        $this->fakeOnlineNamiMembers([
            ['id' => 2334, 'status' => 'Aktiv'],
            ['id' => 2335, 'status' => 'Inaktiv']
        ]);

		$this->postApi('nami/getmembers', ['active' => true, 'inactive' => true])
			->assertSuccess();

        $this->assertDatabaseHas('members', ['nami_id' => 2334]);
        $this->assertDatabaseHas('members', ['nami_id' => 2335]);

        Event::assertDispatched(MemberCreated::class, function($e) {
            return $e->progress == 50 && $e->member->nami_id == 2334;
        });
        Event::assertDispatched(MemberCreated::class, function($e) {
            return $e->progress == 100 && $e->member->nami_id == 2335;
        });
	}

    /** @test */
    public function it_synchs_active_members() {
        Setting::set('namiEnabled', true);

        $this->fakeOnlineNamiMembers([
            ['id' => 2334, 'status' => 'Aktiv'],
            ['id' => 2335, 'status' => 'Inaktiv']
        ]);

        $this->postApi('nami/getmembers', ['active' => true, 'inactive' => false])
            ->assertSuccess();

        $this->assertDatabaseHas('members', ['nami_id' => 2334]);
        $this->assertDatabaseMissing('members', ['nami_id' => 2335]);

        Event::assertDispatched(MemberCreated::class, function($e) {
            return $e->progress == 100 && $e->member->nami_id == 2334;
        });
        Event::assertNotDispatched(MemberCreated::class, function($e) {
            return $e->member->nami_id == 2335;
        });
    }

    /** @test */
    public function it_synchs_inactive_members() {
        Setting::set('namiEnabled', true);

        $this->fakeOnlineNamiMembers([
            ['id' => 2334, 'status' => 'Aktiv'],
            ['id' => 2335, 'status' => 'Inaktiv']
        ]);

        $this->postApi('nami/getmembers', ['active' => false, 'inactive' => true])
            ->assertSuccess();

        $this->assertDatabaseMissing('members', ['nami_id' => 2334]);
        $this->assertDatabaseHas('members', ['nami_id' => 2335]);

        Event::assertDispatched(MemberCreated::class, function($e) {
            return $e->progress == 100 && $e->member->nami_id == 2335;
        });
        Event::assertNotDispatched(MemberCreated::class, function($e) {
            return $e->member->nami_id == 2334;
        });
    }

    /** @test */
    public function it_updates_a_local_nami_member() {
        Setting::set('namiEnabled', true);

        $this->fakeOnlineNamiMembers([
            ['id' => 2334, 'status' => 'Aktiv', 'vorname' => 'Philipp']
        ]);
        $this->create('Member', ['nami_id' => 2334, 'firstname' => 'Max']);

        $this->postApi('nami/getmembers', ['active' => true, 'inactive' => true])
            ->assertSuccess();

        $this->assertDatabaseHas('members', ['nami_id' => 2334, 'firstname' => 'Philipp']);

        Event::assertNotDispatched(MemberUpdated::class, function($e) {
            return $e->member->nami_id == 2334 && $e->member->firstname == 'Philipp';
        });
    }

    /** @test */
    public function it_synchs_no_members_without_any_filter() {
        Setting::set('namiEnabled', true);

        $this->fakeOnlineNamiMembers([
            ['id' => 2334, 'status' => 'Aktiv'],
            ['id' => 2335, 'status' => 'Inaktiv']
        ]);

        $this->postApi('nami/getmembers', ['active' => false, 'inactive' => false])
            ->assertSuccess();

        $this->assertDatabaseMissing('members', ['nami_id' => 2334]);
        $this->assertDatabaseMissing('members', ['nami_id' => 2335]);

        Event::assertNotDispatched(MemberCreated::class);
    }

	/** @test */
	public function it_doesnt_sync_when_nami_is_not_enabled() {
        Setting::set('namiEnabled', false);

        $this->fakeOnlineNamiMembers([
            ['id' => 2334, 'status' => 'Aktiv'],
            ['id' => 2335, 'status' => 'Inaktiv']
        ]);

        $this->postApi('nami/getmembers', ['active' => false, 'inactive' => false])
            ->assertSuccess();

        $this->assertDatabaseMissing('members', ['nami_id' => 2334]);
        $this->assertDatabaseMissing('members', ['nami_id' => 2335]);

        Event::assertNotDispatched(MemberCreated::class);
	}
}
