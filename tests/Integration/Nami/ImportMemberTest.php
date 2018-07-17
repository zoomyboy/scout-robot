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

class ImportMemberTest extends IntegrationTestCase {
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

        $this->membershipManager = M::mock(MembershipManager::class);
        $this->app->instance(MembershipManager::class, $this->membershipManager);
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
        $this->membershipManager->shouldReceive('import')->with(23)->andReturnNull();

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
        $this->membershipManager->shouldReceive('import')->with(23)->andReturnNull();

        $manager = app(MemberManager::class);

        $manager->store(23);

        $this->assertDatabaseHas('members', [
            'nami_id' => 23,
            'gender_id' => null,
            'region_id' => null
        ]);
    }

    /** @test */
    public function it_stores_inactive_nami_members_as_inactive() {
        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->once()->andReturn($this->localNamiMember([
            'id' => 23,
            'status' => 'Inaktiv'
        ]));
        $this->app->instance(MemberReceiver::class, $receiver);
        $this->membershipManager->shouldReceive('import')->with(23)->andReturnNull();

        $manager = app(MemberManager::class);

        $manager->store(23);

        $this->assertDatabaseHas('members', [
            'nami_id' => 23,
            'active' => false
        ]);
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
