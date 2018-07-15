<?php

namespace Tests\Integration\Nami;

use App\Nami\Manager\Member as MemberManager;
use App\Nami\Manager\Membership as MembershipManager;
use App\Nami\Receiver\Member as MemberReceiver;
use App\Nami\Receiver\Membership as MembershipReceiver;
use Illuminate\Support\Facades\Config;
use Tests\IntegrationTestCase;
use Tests\Traits\CreatesNamiMember;
use \Mockery as M;

class MemberManagerImportTest extends IntegrationTestCase {
    use CreatesNamiMember;

    public function setUp() {
        parent::setUp();

        \App\Country::create(['nami_title' => 'NDeutsch', 'nami_id' => 1054, 'title' => 'Deutsch']);
        $default = \App\Country::create(['nami_title' => 'NEng', 'nami_id' => 455, 'title' => 'Englisch']);
        Config::set('seed.default_country', 'Englisch');

        \App\Nationality::create(['nami_title' => 'NDeutsch', 'nami_id' => 334, 'title' => 'Deutsch']);
        \App\Nationality::create(['nami_title' => 'NEng', 'nami_id' => 584, 'title' => 'Englisch']);

        \App\Way::create(['title' => 'Way1']);
        \App\Way::create(['title' => 'Way2']);

        \App\Gender::create(['nami_title' => 'M', 'nami_id' => 100, 'title' => 'M', 'is_null' => false]);
        \App\Gender::create(['nami_title' => 'W', 'nami_id' => 101, 'title' => 'W', 'is_null' => false]);

        \App\Region::create(['nami_title' => 'NRW', 'nami_id' => 200, 'title' => 'NRW', 'is_null' => false]);
        \App\Region::create(['nami_title' => 'BW', 'nami_id' => 201, 'title' => 'BW', 'is_null' => false]);

        \App\Confession::create(['nami_title' => 'RK', 'nami_id' => 300, 'title' => 'RK']);
        \App\Confession::create(['nami_title' => 'E', 'nami_id' => 301, 'title' => 'E']);

        $this->runSeeder(\FeeSeeder::class);
        $this->runSeeder(\SubscriptionSeeder::class);

        $this->runSeeder(\ConfSeeder::class);
    }

    private function localNamiMember($overrides = []) {
        return $this->createNamiMember(array_merge([
            'landId' => 1054,
            'staatsangehoerigkeitId' => 584
        ], $overrides));
    }

    /** @test */
    public function the_manager_can_store_a_nami_response_as_a_member() {
        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->once()->andReturn($this->localNamiMember([
            'id' => 23,
            'vorname' => 'Philipp',
            'nachname' => 'Lang',
            'spitzname' => 'Pille',
            'staatsangehoerigkeitId' => 584,
            'eintrittsdatum' => '2014-06-04 00:00:00',
            'geburtsDatum' => '2000-12-15 00:00:00',
            'wiederverwendenFlag' => true,
            'zeitschriftenversand' => true,
            'strasse' => 'Itterstr',
            'plz' => '45454',
            'ort' => 'SG',
            'staatsangehoerigkeitText' => 'Staat',
            'nameZusatz' => 'Zusatz',
            'telefon1' => 'Tel1',
            'telefon2' => 'Tel2',
            'telefon3' => 'Tel3',
            'telefax' => 'Fax4',
            'email' => 'phlili@aa.de',
            'emailVertretungsberechtigter' => 'parents@aa.de',
            'status' => 'Aktiv',
            'geschlechtId' => 101,
            'regionId' => 201,
            'konfessionId' => 301,
            'beitragsartId' => 2,
            'landId' => 1054    // Deutsch
        ]));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->store(23);

        $this->assertDatabaseHas('members', [
            'nami_id' => 23,
            'firstname' => 'Philipp',
            'lastname' => 'Lang',
            'nickname' => 'Pille',
            'country_id' => 1,
            'nationality_id' => 2,
            'joined_at' => '2014-06-04 00:00:00',
            'birthday' => '2000-12-15 00:00:00',
            'keepdata' => true,
            'sendnewspaper' => true,
            'address' => 'Itterstr',
            'zip' => 45454,
            'city' => 'SG',
            'other_country' => 'Staat',
            'further_address' => 'Zusatz',
            'phone' => 'Tel1',
            'mobile' => 'Tel2',
            'business_phone' => 'Tel3',
            'fax' => 'Fax4',
            'email' => 'phlili@aa.de',
            'email_parents' => 'parents@aa.de',
            'active' => true,
            'gender_id' => 2,
            'region_id' => 2,
            'confession_id' => 2,
            'subscription_id' => 2
        ]);
    }

    /** @test */
    public function it_doesnt_set_the_gender_or_region_when_nami_returns_null_value() {
        $noGender = $this->create('Gender', ['is_null' => true, 'nami_id' => 89]);
        $noRegion = $this->create('Region', ['is_null' => true, 'nami_id' => 90]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->once()->andReturn($this->localNamiMember([
            'id' => 23,
            'geschlechtId' => 89,
            'regionId' => 90
        ]));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->store(23);

        $this->assertDatabaseHas('members', [
            'nami_id' => 23,
            'gender_id' => null,
            'region_id' => null
        ]);
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

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->once()->andReturn($this->localNamiMember([
            'id' => 23,
            'geschlechtId' => 89,
            'regionId' => 90
        ]));
        $this->app->instance(MemberReceiver::class, $receiver);

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
