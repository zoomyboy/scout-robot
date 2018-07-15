<?php

namespace Tests\Integration\Nami;

use App\Nami\Manager\Membership as MembershipManager;
use App\Nami\Receiver\Membership as MembershipReceiver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Tests\IntegrationTestCase;
use Tests\Traits\CreatesNamiMember;
use \Mockery as M;

class MemberManagerImportMembershipTest extends IntegrationTestCase {
    use CreatesNamiMember;

    public function setUp() {
        parent::setUp();

        $this->runSeeder(\DatabaseSeeder::class);

        $this->member = $this->create('Member', ['nami_id' => 23]);

        $this->activities = $this->createMany('Activity', 2, [
            ['nami_id' => 251], ['nami_id' => 252]
        ]);

        $this->groups = $this->createMany('Group', 2, [
            ['nami_id' => 301], ['nami_id' => 401]
        ]);

        $this->activities[0]->groups()->attach($this->groups[0]);
        $this->activities[1]->groups()->attach($this->groups[1]);
    }

    /** @test */
    public function it_imports_all_memberships_that_are_active() {
        $endingDate = Carbon::now()->addDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(23)->once()
            ->andReturn(collect(json_decode('[{"id": 588}, {"id": 589}]')));

        $receiver->shouldReceive('single')->with(588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 301
        ]);

        $receiver->shouldReceive('single')->with(589)->once()->andReturn((object)[
            'aktivVon' => '2017-01-01 00:00:00',
            'aktivBis' => $endingDate,
            'id' => 589,
            'taetigkeitId' => 252,
            'untergliederungId' => 401
        ]);
        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->store(23);

        $this->assertDatabaseHas('memberships', [
            'activity_id' => $this->activities[0]->id,
            'group_id' => $this->groups[0]->id,
            'nami_id' => 588,
            'member_id' => $this->member->id
        ]);

        $this->assertDatabaseHas('memberships', [
            'activity_id' => $this->activities[1]->id,
            'group_id' => $this->groups[1]->id,
            'nami_id' => 589,
            'member_id' => $this->member->id
        ]);
    }

    private function localNamiMember($overrides = []) {
        return $this->createNamiMember(array_merge([
            'landId' => 1054,
            'staatsangehoerigkeitId' => 584
        ], $overrides));
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_doesnt_exist_locally() {
        $endingDate = Carbon::now()->addDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(23)->once()
            ->andReturn(collect(json_decode('[{"id": 588}, {"id": 589}]')));

        $receiver->shouldReceive('single')->with(588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 999,
            'untergliederungId' => 301
        ]);

        $receiver->shouldReceive('single')->with(589)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 999
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->store(23);

        $this->assertDatabaseMissing('memberships', [
            'member_id' => $this->member->id
        ]);
    }

    /** @test */
    public function it_imports_a_members_membership_that_has_valid_activity_but_no_group() {
        $endingDate = Carbon::now()->addDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(23)->once()
            ->andReturn(collect(json_decode('[{"id": 588}]')));

        $receiver->shouldReceive('single')->with(588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 251
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->store(23);

        $this->assertDatabaseHas('memberships', [
            'member_id' => $this->member->id,
            'activity_id' => $this->activities[0]->id,
            'group_id' => null
        ]);
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_has_a_valid_taetigkeit_but_no_local_matching_group() {
        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(23)->once()
            ->andReturn(collect(json_decode('[{"id": 588}, {"id": 589}]')));

        $receiver->shouldReceive('single')->with(588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 401
        ]);

        $receiver->shouldReceive('single')->with(589)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 589,
            'taetigkeitId' => 251,
            'untergliederungId' => 9999
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->store(23);

        $this->assertDatabaseMissing('memberships', [
            'member_id' => $this->member->id
        ]);
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_has_a_valid_taetigkeit_but_is_not_active() {
        $endingDate = Carbon::now()->subDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(23)->once()
            ->andReturn(collect(json_decode('[{"id": 588}]')));

        $receiver->shouldReceive('single')->with(588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => $endingDate,
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 301
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->store(23);

        $this->assertDatabaseMissing('memberships', [
            'member_id' => $this->member->id
        ]);
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
