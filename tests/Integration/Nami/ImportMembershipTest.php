<?php

namespace Tests\Integration\Nami;

use App\Member;
use App\Membership;
use App\Nami\Manager\Membership as MembershipManager;
use App\Nami\Receiver\Membership as MembershipReceiver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Tests\Integration\NamiTestCase;
use \Mockery as M;

class ImportMembershipTest extends NamiTestCase {
    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();

        $this->member = $this->create('Member', ['nami_id' => 5678]);

        $this->activities = $this->createMany('Activity', 2, [
            ['nami_id' => 251], ['nami_id' => 252]
        ]);

        $this->groups = $this->createMany('Group', 3, [
            ['nami_id' => 301], ['nami_id' => 401], ['nami_id' => 501]
        ]);

        $this->activities[0]->groups()->attach($this->groups[0]);
        $this->activities[1]->groups()->attach($this->groups[1]);
        $this->activities[1]->groups()->attach($this->groups[1]);
    }

    /** @test */
    public function it_imports_all_memberships_that_are_active() {
        $endingDate = Carbon::now()->addDays(10)->format('Y-m-d').' 00:00:00';

        $this->fakeOnlineNamiMembers([
            ['memberships' => [
                [],
                [
                    'aktivBis' => $endingDate,
                    'id' => 589,
                    'taetigkeitId' => 252,
                    'untergliederungId' => 401
                ]
            ]]
        ]);

        app(MembershipManager::class)->pull(5678);

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

    /** @test */
    public function it_updates_a_local_membership() {
        $membership = new Membership([
            'nami_id' => 588,
            'activity_id' => $this->activities[0]->id,
            'group_id' => $this->groups[0]->id
        ]);
        Member::nami(5678)->first()->memberships()->save($membership);

        $endingDate = Carbon::now()->addDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(5678)->once()
            ->andReturn(collect(json_decode('[{"id": 588}]')));

        $receiver->shouldReceive('single')->with(5678, 588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 252,
            'untergliederungId' => 401
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->pull(5678);

        $this->assertDatabaseHas('memberships', [
            'activity_id' => $this->activities[1]->id,
            'group_id' => $this->groups[1]->id,
            'nami_id' => 588,
            'member_id' => $this->member->id
        ]);
        $this->assertDatabaseMissing('memberships', [
            'group_id' => $this->groups[0]->id,
            'nami_id' => 588,
        ]);
    }

    /** @test */
    public function it_deletes_a_local_membership_that_has_ended() {
        $membership = new Membership([
            'nami_id' => 588,
            'activity_id' => $this->activities[0]->id,
            'group_id' => $this->groups[0]->id
        ]);
        Member::nami(5678)->first()->memberships()->save($membership);

        $endingDate = Carbon::now()->subDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(5678)->once()
            ->andReturn(collect(json_decode('[{"id": 588}]')));

        $receiver->shouldReceive('single')->with(5678, 588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => $endingDate,
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 301
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->pull(5678);

        $this->assertDatabaseMissing('memberships', [
            'member_id' => $this->member->id
        ]);
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_doesnt_exist_locally() {
        $endingDate = Carbon::now()->addDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(5678)->once()
            ->andReturn(collect(json_decode('[{"id": 588}, {"id": 589}]')));

        $receiver->shouldReceive('single')->with(5678, 588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 999,
            'untergliederungId' => 301
        ]);

        $receiver->shouldReceive('single')->with(5678, 589)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 999
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->pull(5678);

        $this->assertDatabaseMissing('memberships', [
            'member_id' => $this->member->id
        ]);
    }

    /** @test */
    public function it_imports_a_members_membership_that_has_valid_activity_but_no_group() {
        $endingDate = Carbon::now()->addDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(5678)->once()
            ->andReturn(collect(json_decode('[{"id": 588}]')));

        $receiver->shouldReceive('single')->with(5678, 588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 251
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->pull(5678);

        $this->assertDatabaseHas('memberships', [
            'member_id' => $this->member->id,
            'activity_id' => $this->activities[0]->id,
            'group_id' => null
        ]);
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_has_a_valid_taetigkeit_but_no_local_matching_group() {
        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(5678)->once()
            ->andReturn(collect(json_decode('[{"id": 588}, {"id": 589}]')));

        $receiver->shouldReceive('single')->with(5678, 588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 401
        ]);

        $receiver->shouldReceive('single')->with(5678, 589)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 589,
            'taetigkeitId' => 251,
            'untergliederungId' => 9999
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->pull(5678);

        $this->assertDatabaseMissing('memberships', [
            'member_id' => $this->member->id
        ]);
    }

    /** @test */
    public function it_doesnt_import_a_members_membership_that_has_a_valid_taetigkeit_but_is_not_active() {
        $endingDate = Carbon::now()->subDays(10)->format('Y-m-d').' 00:00:00';

        $receiver = M::mock(MembershipReceiver::class);
        $receiver->shouldReceive('all')->with(5678)->once()
            ->andReturn(collect(json_decode('[{"id": 588}]')));

        $receiver->shouldReceive('single')->with(5678, 588)->once()->andReturn((object)[
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => $endingDate,
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 301
        ]);

        $this->app->instance(MembershipReceiver::class, $receiver);

        app(MembershipManager::class)->pull(5678);

        $this->assertDatabaseMissing('memberships', [
            'member_id' => $this->member->id
        ]);
    }
}
