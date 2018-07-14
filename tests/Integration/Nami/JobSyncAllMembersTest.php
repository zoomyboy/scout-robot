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
use Tests\IntegrationTestCase;
use \Mockery as M;

class JobSyncAllMembersTest extends IntegrationTestCase {
    public $config;

    public function setUp() {
        parent::setUp();

        $this->runSeeder(\DatabaseSeeder::class);
    }

    /** @test */
    public function it_saves_or_upates_a_member_when_the_job_executes() {
        $this->setting('defaultWay', 1);

        $this->create('Member', ['nami_id' => 2335]);

        $memberReceiver = M::mock(MemberReceiver::class);
        $memberReceiver->shouldReceive('all')->once()->andReturn([
            (object) ['id' => 2334],
            (object) ['id' => 2335]
        ]);
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
    public function it_saves_a_members_lastname() {
        $this->authAsApi();

        NaMi::createMember(['nachname' => 'Heut', 'id' => 2334]);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertEquals('Heut', $member->lastname);
    }

    /** @test */
    public function it_saves_a_members_birthday() {
        $this->authAsApi();

        NaMi::createMember(['geburtsDatum' => '2016-04-05 00:00:00', 'id' => 2334]);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertEquals('2016-04-05', $member->birthday->format('Y-m-d'));
    }

    /** @test */
    public function it_saves_a_members_gender() {
        $this->authAsApi();

        NaMi::createMember(['geschlechtId' => 19, 'id' => 2334]);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertEquals('Männlich', $member->gender->title);
    }

    /** @test */
    public function it_sets_a_members_gender_to_null_when_gender_is_keine_angabe() {
        $this->authAsApi();

        NaMi::createMember(['geschlechtId' => 23, 'id' => 2334]);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertNull($member->gender);
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_doesnt_exist_locally() {
        $this->authAsApi();

        NaMi::createMember(['id' => 2334]);
        NaMi::createMembership(2334, ['taetigkeitId' => 999, 'untergliederungId' => 999]);
        NaMi::createMembership(2334, ['taetigkeitId' => 999, 'untergliederungId' => null]);
        NaMi::createMembership(2334, ['taetigkeitId' => null, 'untergliederungId' => 999]);
        NaMi::createMembership(2334, ['taetigkeitId' => null, 'untergliederungId' => null]);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertCount(0, $member->memberships);
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_has_a_valid_taetigkeit_but_no_local_matching_group() {
        $this->authAsApi();

        NaMi::createMember([ 'id' => 2334]);
        //Vorsitzende/r und Rover
        NaMi::createMembership(2334, ['taetigkeitId' => 13, 'untergliederungId' => 4]);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertCount(0, $member->memberships);
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_has_a_valid_taetigkeit_but_is_not_active() {
        $this->authAsApi();

        NaMi::createMember(['id' => 2334]);
        NaMi::createMembership(2334, ['aktivBis' => \Carbon\Carbon::now()->subDays(1)->format('Y-m-d').'T00:00:00']);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertCount(0, $member->memberships);
    }

    /** @test */
    public function it_imports_a_membership_that_is_valid() {
        $this->authAsApi();

        NaMi::createMember(['id' => 2334]);
        NaMi::createMembership(2334, ['taetigkeitId' => 6, 'untergliederungId' => 4]);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertCount(1, $member->memberships);

        $membership = $member->memberships->first();
        $this->assertEquals(4, $membership->group->nami_id);
        $this->assertEquals(6, $membership->activity->nami_id);
    }

    /** @test */
    public function it_imports_each_membership_only_once_when_there_are_multiple_ones() {
        $this->authAsApi();

        NaMi::createMember(['id' => 2334]);
        NaMi::createMembership(2334, ['taetigkeitId' => 6, 'untergliederungId' => 4]);
        NaMi::createMembership(2334, ['taetigkeitId' => 6, 'untergliederungId' => 3]);

        SyncAllNaMiMembers::dispatch();

        $member = Member::where('nami_id', 2334)->first();
        $this->assertNotNull($member);
        $this->assertCount(2, $member->memberships);

        $membership = $member->memberships->first();
        $this->assertEquals(4, $membership->group->nami_id);
        $this->assertEquals(6, $membership->activity->nami_id);

        $membership = $member->memberships[1];
        $this->assertEquals(3, $membership->group->nami_id);
        $this->assertEquals(6, $membership->activity->nami_id);
    }

    //------------------------------ Overwrite Members ------------------------------
    //*******************************************************************************
    /** @test */
    public function it_overwrites_a_members_firstname_that_is_found_locally_by_nami_id() {
        NaMi::createMember(['vorname' => 'Hans', 'id' => 2334]);

        SyncAllNaMiMembers::dispatch();

        $this->assertNotNull(Member::where('firstname', 'Hans')->first());
        $this->assertCount(1, Member::get());

        NaMi::updateMember(2334, ['vorname' => 'Peter']);

        SyncAllNaMiMembers::dispatch();

        $this->assertCount(1, Member::get());
        $this->assertNotNull(Member::where('firstname', 'Peter')->first());
    }

    /** @test */
    public function it_only_stores_active_members() {
        $this->authAsApi();

        NaMi::createMember(['nachname' => 'Heut', 'id' => 2334, 'status' => 'Aktiv']);
        NaMi::createMember(['nachname' => 'Heut2', 'id' => 2335, 'status' => 'Inaktiv']);

        SyncAllNaMiMembers::dispatch([
            'status' => 'Aktiv'
        ]);

        $activeMember = Member::where('nami_id', 2334)->first();
        $inactiveMember = Member::where('nami_id', 2335)->first();
        $this->assertNotNull($activeMember);
        $this->assertNull($inactiveMember);
    }

    /** @test */
    public function it_stores_active_state_of_members() {
        $this->authAsApi();

        NaMi::createMember(['nachname' => 'Heut', 'id' => 2334, 'status' => 'Aktiv']);
        NaMi::createMember(['nachname' => 'Heut2', 'id' => 2335, 'status' => 'Inaktiv']);

        SyncAllNaMiMembers::dispatch([
            'status' => 'Aktiv|Inaktiv'
        ]);

        $activeMember = Member::where('nami_id', 2334)->first();
        $inactiveMember = Member::where('nami_id', 2335)->first();
        $this->assertNotNull($activeMember);
        $this->assertNotNull($inactiveMember);
        $this->assertFalse($inactiveMember->active);
        $this->assertTrue($activeMember->active);
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
