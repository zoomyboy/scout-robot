<?php

namespace Tests\Integration\Nami;

use App\Nami\Manager\Member as MemberManager;
use App\Nami\Manager\Membership as MembershipManager;
use App\Nami\Receiver\Member as MemberReceiver;
use App\Nami\Receiver\Membership as MembershipReceiver;
use Illuminate\Support\Facades\Config;
use Tests\Integration\NamiTestCase;
use Tests\Traits\CreatesNamiMember;
use \Mockery as M;

class ImportMemberTest extends NamiTestCase {
    use CreatesNamiMember;

    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();
        $this->membershipManager = M::mock(MembershipManager::class);
        $this->app->instance(MembershipManager::class, $this->membershipManager);
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

        $manager->pull(23);

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

        $manager->pull(23);

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

        $manager->pull(23);

        $this->assertDatabaseHas('members', [
            'nami_id' => 23,
            'active' => false
        ]);
    }
}
