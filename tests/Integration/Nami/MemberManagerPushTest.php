<?php

namespace Tests\Integration\Nami;

use App\Nami\Manager\Member as MemberManager;
use App\Nami\Manager\Membership as MembershipManager;
use App\Nami\Receiver\Member as MemberReceiver;
use App\Nami\Receiver\Membership as MembershipReceiver;
use Illuminate\Support\Facades\Config;
use Tests\Integration\NamiTestCase;
use \Mockery as M;

class MemberManagerPushTest extends NamiTestCase {
    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();
        $this->membershipManager = M::mock(MembershipManager::class);
        $this->app->instance(MembershipManager::class, $this->membershipManager);
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
    public function the_manager_receives_the_prename_when_a_member_is_pushed() {
        $localMember = $this->create('Member', ['nami_id' => 23, 'firstname' => 'Philipp']);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember(['vorname' => 'Philippalt']));
        $receiver->shouldReceive('update')
            ->with(23, M::on(function($m) {
                return $m['vorname'] == 'Philipp';
            }))
            ->once();
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_can_update_a_members_firstname() {
        $localMember = $this->create('Member', ['nami_id' => 23, 'firstname' => 'Philipp2']);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
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
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'vorname')  == 'Philipp2';
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }

    /** @test */
    public function it_can_update_a_members_lastname() {
        $localMember = $this->create('Member', ['nami_id' => 23, 'lastname' => 'Philipp2']);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
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
        ]));
        $receiver->shouldReceive('update')->with(23, M::on(function($m) {
            return array_get($m, 'nachname')  == 'Philipp2';
        }));
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }
}
