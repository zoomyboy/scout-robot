<?php

namespace Tests\Integration\Nami;

use App\Events\Import\MemberCreated;
use App\Events\Import\MemberUpdated;
use App\Jobs\SyncAllNamiMembers;
use App\Member;
use App\Nami\Manager\Member as MemberManager;
use App\Nami\Manager\Membership as MembershipManager;
use App\Nami\Receiver\Member as MemberReceiver;
use App\Nami\Receiver\Membership as MembershipReceiver;
use Illuminate\Support\Facades\Event;
use Tests\Integration\NamiTestCase;
use \Mockery as M;

class JobSyncAllMembersTest extends NamiTestCase {
    public $config;

    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();
    }

    /** @test */
    public function it_saves_or_upates_a_member_when_the_job_executes() {
        $this->create('Member', ['nami_id' => 2335]);

        $memberReceiver = M::mock(MemberReceiver::class);
        $memberReceiver->shouldReceive('all')->once()->andReturn(collect([
            (object) ['id' => 2334, 'entries_status' => 'Aktiv'],
            (object) ['id' => 2335, 'entries_status' => 'Inaktiv']
        ]));
        $this->app->instance(MemberReceiver::class, $memberReceiver);

        $firstMemberModel = new Member(['nami_id' => 2334]);
        $secondMemberModel = new Member(['nami_id' => 2335]);

        $memberManager = M::mock(MemberManager::class);
        $memberManager->shouldReceive('store')->once()->with(2334)->andReturn($firstMemberModel);
        $memberManager->shouldReceive('update')->once()->with(2335)->andReturn($secondMemberModel);
        $this->app->instance(MemberManager::class, $memberManager);

        SyncAllNamiMembers::dispatch();

        Event::assertDispatched(MemberCreated::class, function($e) {
            return $e->progress == 50 && $e->member->nami_id == 2334;
        });
        Event::assertDispatched(MemberUpdated::class, function($e) {
            return $e->progress == 100 && $e->member->nami_id == 2335;
        });
    }

    /** @test */
    public function it_only_stores_active_members() {
        $memberReceiver = M::mock(MemberReceiver::class);
        $memberReceiver->shouldReceive('all')->once()->andReturn(collect([
            (object) ['id' => 2334, 'entries_status' => 'Aktiv'],
            (object) ['id' => 2335, 'entries_status' => 'Inaktiv']
        ]));
        $this->app->instance(MemberReceiver::class, $memberReceiver);

        $firstMemberModel = new Member(['nami_id' => 2334]);

        $memberManager = M::mock(MemberManager::class);
        $memberManager->shouldReceive('store')->once()->with(2334)->andReturn($firstMemberModel);
        $memberManager->shouldReceive('store')->with(2335)->never();
        $this->app->instance(MemberManager::class, $memberManager);

        dispatch(new SyncAllNamiMembers(['status' => ['Aktiv']]));

        Event::assertDispatched(MemberCreated::class, function($e) {
            return $e->progress == 100 && $e->member->nami_id == 2334;
        });
        Event::assertNotDispatched(MemberCreated::class, function($e) {
            return $e->member->nami_id == 2335;
        });
    }

    //------------------------------ Overwrite Members ------------------------------
    //*******************************************************************************
    /** @test */
    public function it_overwrites_a_local_model_that_is_found_by_nami_id() {
        $memberReceiver = M::mock(MemberReceiver::class);
        $memberReceiver->shouldReceive('all')->once()->andReturn(collect([
            (object) ['id' => 2334, 'entries_status' => 'Aktiv']
        ]));
        $this->app->instance(MemberReceiver::class, $memberReceiver);

        $localMember = $this->create('Member', ['nami_id' => 2334]);

        $memberManager = M::mock(MemberManager::class);
        $memberManager->shouldReceive('update')->once()->with(2334)->andReturn($localMember);
        $this->app->instance(MemberManager::class, $memberManager);

        dispatch(new SyncAllNamiMembers(['status' => ['Aktiv']]));

        Event::assertDispatched(MemberUpdated::class, function($e) {
            return $e->progress == 100 && $e->member->nami_id == 2334;
        });
    }

    /** @test */
    public function it_stores_subscription_of_members() {
        $this->authAsApi();

        Subscription::truncate();
        Fee::truncate();

        NaMi::createMember(['nachname' => 'Heut1', 'id' => 2334, 'status' => 'Aktiv', 'beitragsartId' => 50]);
        NaMi::createMember(['nachname' => 'Heut2', 'id' => 2335, 'status' => 'Aktiv', 'beitragsartId' => 60]);
        NaMi::createMember(['nachname' => 'Heut3', 'id' => 2336, 'status' => 'Aktiv', 'beitragsartId' => 70]);
        NaMi::createMember(['nachname' => 'Heut4', 'id' => 2337, 'status' => 'Aktiv', 'beitragsartId' => 100]);

        $f1 = Fee::create(['title' => 'test1', 'nami_id' => 50]);
        $f2 = Fee::create(['title' => 'test2', 'nami_id' => 60]);
        $f3 = Fee::create(['title' => 'test3', 'nami_id' => 70]);

        $s1 = \App\Subscription::create(['title' => 'sub1', 'amount' => 10]);
        $s1->fee()->associate($f1);
        $s1->save();

        $s2 = \App\Subscription::create(['title' => 'sub2', 'amount' => 10]);
        $s2->fee()->associate($f2);
        $s2->save();


        SyncAllNaMiMembers::dispatch([
            'status' => 'Aktiv|Inaktiv'
        ]);

        $member = Member::where('nami_id', 2334)->first();
        $this->assertEquals('sub1', $member->subscription->title);

        $member = Member::where('nami_id', 2335)->first();
        $this->assertEquals('sub2', $member->subscription->title);

        $member = Member::where('nami_id', 2336)->first();
        $this->assertNull($member->subscription);

        $member = Member::where('nami_id', 2337)->first();
        $this->assertNull($member->subscription);
    }
}
