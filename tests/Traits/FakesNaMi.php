<?php

namespace Tests\Traits;

use Mockery as M;
use Tests\Utilities\NaMiFake;
use App\Nami\Interfaces\UserResolver;
use App\Nami\Receiver\Member as MemberReceiver;
use App\Nami\Receiver\Membership as MembershipReceiver;

trait FakesNaMi {
    public function setupNamiDatabaseModels() {
        $this->createCountries();
        $this->createNationalities();
        $this->createWays();

        \App\Gender::create(['nami_title' => 'M', 'nami_id' => 100, 'title' => 'M', 'is_null' => false]);
        \App\Gender::create(['nami_title' => 'W', 'nami_id' => 101, 'title' => 'W', 'is_null' => false]);

        $this->createRegions();
        $this->createConfessions();
        $this->createSubscriptions();
        $this->runSeeder(\ConfSeeder::class);
    }

    public function setUpActivityGroups() {
        $this->runSeeder(\ActivitySeeder::class);
    }

    public function fakeOnlineNamiMembers($members) {
        $memberReceiver = M::mock(MemberReceiver::class);
        $membershipReceiver = M::mock(MembershipReceiver::class);

        $allMembers = collect($members)->map(function($member) {
            return (object) [
                'id' => $member['id'] ?? 5678,
                'entries_status' => $member['status'] ?? 'Aktiv'
            ];
        });

        $memberReceiver->shouldReceive('all')->andReturn($allMembers);

        foreach($members as $member) {
            $member = $this->createNamiMember($member);
            $memberReceiver->shouldReceive('single')->with($member->id)
                ->andReturn($member);

            $memberMemberships = $member->memberships ?? [];
            $allMemberships = collect($memberMemberships)->map(function($membership) {
                return (object) ['id' => $membership['id'] ?? 588];
            });
            $membershipReceiver->shouldReceive('all')->with($member->id)
                ->andReturn($allMemberships);
            foreach($memberMemberships as $membership) {
                $membershipReceiver->shouldReceive('single')->with($member->id, $membership['id'] ?? 588)
                    ->andReturn($this->createNamiMembership($membership));
            }
        }

        $this->app->instance(MemberReceiver::class, $memberReceiver);
        $this->app->instance(MembershipReceiver::class, $membershipReceiver);
    }

    public function createNamiMembership($overrides = []) {
        return (object) array_merge([
            'aktivVon' => '2016-01-01 00:00:00',
            'aktivBis' => "",
            'id' => 588,
            'taetigkeitId' => 251,
            'untergliederungId' => 301
        ], $overrides);
    }

    public function createNamiMember($overrides = []) {
        return (object) array_merge([
            'austrittsDatum' => '',
            'beitragsartId' => 1,
            'ersteTaetigkeitId' => null,
            'ersteUntergliederung' => 'Jungpfadfinder',
            'ersteUntergliederungId' => null,
            'emailVertretungsberechtigter' => 'eltern@test.de',
            'genericField1' => '',
            'genericField2' => '',
            'geschlecht' => 'männlich',
            'jungpfadfinder' => null,
            'landId' => 1054,
            'lastUpdated' => '2016-01-20 19:45:24',
            'mglType' => 'Mitglied',
            'ersteTaetigkeit' => null,
            'nameZusatz' => 'Zusatz',
            'id' => 5678,
            'version' => 30,
            'gruppierungId' => 100105,
            'mglTypeId' => 'MITGLIED',
            'beitragsart' => 'Voller Beitrag',
            'nachname' => 'Mustermann',
            'plz' => '42777',
            'eintrittsdatum' =>'2015-05-27 00:00:00',
            'rover' => null,
            'region' => 'Nordrhein-Westfalen (Deutschland)',
            'status' => 'Aktiv',
            'konfession' => 'evangelisch / protestantisch',
            'konfessionId' => 300,
            'fixBeitrag' => null,
            'zeitschriftenversand' => true,
            'pfadfinder' => null,
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
            "geschlechtId" => 100,
            'land' => 'Deutschland',
            'email' => 'member@test.de',
            'telefax' => '+49 212 4455555',
            'telefon1' => '+49 212 319345',
            'telefon2' => '+49 163 1725774',
            'telefon3' => '+49 222 33333',
            'woelfling' => null,
            'strasse' => 'Strasse 1',
            'vorname' => 'Max',
            'mitgliedsNummer' => 245852,
            'gruppierung' => 'Solingen-Wald, Silva 100105',
            'geburtsDatum' => '2005-12-28 00:00:00',
            'ort' => 'City',
            'regionId' => 200,
            'sonst01' => false,
            'sonst02' => false,
            'staatsangehoerigkeit' => 'deutsch',
            'staatsangehoerigkeitId' => 584,
            'stufe' => 'Jungpfadfinder',
            'spitzname' => 'Spitz',
            'staatsangehoerigkeitText' => 'Andere',
            'wiederverwendenFlag' => true,
        ], $overrides);
    }

    public function mockNamiUser($group) {
        $resolver = M::mock(UserResolver::class);
        $resolver->shouldReceive('getGroup')->andReturn($group);
        $this->app->instance(UserResolver::class, $resolver);
    }
}
