<?php

namespace Tests\Integration\Nami;

use App\Nami\Manager\Member as MemberManager;
use App\Nami\Manager\Membership as MembershipManager;
use App\Nami\Receiver\Member as MemberReceiver;
use App\Nami\Receiver\Membership as MembershipReceiver;
use Illuminate\Support\Facades\Config;
use Tests\Integration\NamiTestCase;
use \Mockery as M;
use Carbon\Carbon;

class MemberManagerPushTest extends NamiTestCase {
    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();
        $this->membershipManager = M::mock(MembershipManager::class);
        $this->app->instance(MembershipManager::class, $this->membershipManager);

        /* [
            'austrittsDatum' => '',
            'beitragsart' => 'Voller Beitrag',
            'beitragsartId' => 1,
            'eintrittsdatum' =>'2015-05-27 00:00:00',
            'email' => 'member@test.de',
            'emailVertretungsberechtigter' => 'eltern@test.de',
            'ersteTaetigkeit' => null,
            'ersteTaetigkeitId' => null,
            'ersteUntergliederung' => 'Jungpfadfinder',
            'ersteUntergliederungId' => null,
            'fixBeitrag' => null,
            'geburtsDatum' => '2005-12-28 00:00:00',
            'genericField1' => '',
            'genericField2' => '',
            'geschlecht' => 'männlich',
            "geschlechtId" => 19,
            'gruppierung' => 'Solingen-Wald, Silva 100105',
            'gruppierungId' => 100105,
            'id' => 23,
            'jungpfadfinder' => null,
            'konfession' => 'evangelisch / protestantisch',
            'konfessionId' => 2,
            'kontoverbindung' => (object)[
                'id' => 227580,
                'institut' => '',
                'bankleitzahl' => '',
                'kontonummer' => '',
                'iban' => '',
                'bic' => '',
                'kontoinhaber' => '',
                'mitgliedsNummer' => 245852,
                'zahlungsKonditionId' => null,
                'zahlungsKondition' => null
            ],
            'land' => 'Deutschland',
            'landId' => 1,
            'lastUpdated' => '2016-01-20 19:45:24',
            'mglType' => 'Mitglied',
            'mglTypeId' => 'MITGLIED',
            'mitgliedsNummer' => 245852,
            'nachname' => 'Mustermann',
            'nameZusatz' => 'Zusatz',
            'ort' => 'City',
            'pfadfinder' => null,
            'plz' => '42777',
            'region' => 'Nordrhein-Westfalen (Deutschland)',
            'regionId' => 10,
            'rover' => null,
            'sonst01' => false,
            'sonst02' => false,
            'spitzname' => 'Spitz',
            'staatsangehoerigkeit' => 'deutsch',
            'staatsangehoerigkeitId' => 1054,
            'staatsangehoerigkeitText' => 'Andere',
            'status' => 'Aktiv',
            'strasse' => 'Strasse 1',
            'stufe' => 'Jungpfadfinder',
            'telefax' => '+49 212 4455555',
            'telefon1' => '+49 212 319345',
            'telefon2' => '+49 163 1725774',
            'telefon3' => '+49 222 33333',
            'version' => 30,
            'vorname' => 'Max',
            'wiederverwendenFlag' => true,
            'woelfling' => null,
            'zeitschriftenversand' => true,
        ]*/
    }

    /** @test */
    public function the_manager_doesnt_do_anything_if_a_member_without_a_nami_id_is_pushed() {
        $localMember = $this->create('Member', ['nami_id' => null]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('update')->never();
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_austrittsDatum() {
        $localMember = $this->create('Member', ['nami_id' => 23]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'austrittsDatum' => ''
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'austrittsDatum')  == '';
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_beitragsart() {
        $localMember = $this->create('Member', ['nami_id' => 23]);


        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'beitragsart' => 'T'
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'beitragsart')  == 'T';
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_beitragsartId() {
        $subscription = \App\Subscription::where('title', 'Familie')->first();
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'subscription_id' => $subscription->id
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'beitragsartId' => 999
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) use ($subscription) {
            return array_get($m, 'beitragsartId')  == $subscription->fee->nami_id;
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_geschlecht() {
        $gender = \App\Gender::where('title', 'M')->first();
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'gender_id' => $gender->id
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'geschlechtId' => 999
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) use ($gender) {
            return array_get($m, 'geschlechtId')  == $gender->nami_id;
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_geschlecht_to_null() {
        $gender = $this->create('Gender', ['nami_id' => 66, 'is_null' => true]);
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'gender_id' => null
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'geschlechtId' => 999
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'geschlechtId')  == 66;
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_region() {
        $region = \App\Region::where('title', 'BW')->first();
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'region_id' => $region->id
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'regionId' => 999
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) use ($region) {
            return array_get($m, 'regionId')  == $region->nami_id;
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_region_id_to_null() {
        $region = $this->create('Region', ['is_null' => true, 'nami_id' => 56]);
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'region_id' => null
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'regionId' => 999
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'regionId') == 56;
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_eintrittsdatum() {
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'joined_at' => '2018-07-07'
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'eintrittsdatum' => '2018-99-99'
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'eintrittsdatum')  == '2018-07-07T00:00:00';
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_email() {
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'email' => 'pille@aa.de'
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'email' => 'pp@aaa.de'
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'email')  == 'pille@aa.de';
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_emailVertretungsberechtigter() {
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'email_parents' => 'pille@aa.de'
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'emailVertretungsberechtigter' => 'pp@aaa.de'
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'emailVertretungsberechtigter')  == 'pille@aa.de';
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_sets_the_members_birthday() {
        $localMember = $this->create('Member', [
            'nami_id' => 23,
            'birthday' => '2018-07-06 00:00:00'
        ]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'geburtsDatum' => '2018-06-03 00:00:00'
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'geburtsDatum')  === '2018-07-06 00:00:00';
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }
}
