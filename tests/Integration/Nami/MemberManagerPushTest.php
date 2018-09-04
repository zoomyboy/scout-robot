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
    public function the_manager_receives_the_proper_nami_input_data_when_a_member_is_pushed() {
        $localMember = $this->create('Member', ['nami_id' => 23]);

        $receiver = M::mock(MemberReceiver::class);
        $receiver->shouldReceive('single')->with(23)->andReturn($this->localNamiMember([
            'ersteTaetigkeitId' => 'Tae',
            'ersteUntergliederungId' => 'Unt',
            'fixBeitrag' => 'Fix'
        ]));
        $receiver->shouldReceive('update')->with(23, [
            'id' => 23,
            'vorname' => $localMember->firstname,
            'beitragsartId' => $localMember->subscription->fee->nami_id,
            'eintrittsdatum' => $localMember->joined_at->format('Y-m-d').' 00:00:00',
            'email' => $localMember->email,
            'emailVertretungsberechtigter' => $localMember->email_parents,
            'ersteTaetigkeitId' => 'Tae',
            'ersteUntergliederungId' => 'Unt',
            'fixBeitrag' => 'Fix',
            'geburtsDatum' => $localMember->birthday->format('Y-m-d').' 00:00:00',
            'geschlechtId' => $localMember->gender->nami_id,
            'regionId' => $localMember->region->nami_id,
            'konfessionId' => $localMember->confession->nami_id,
            'landId' => $localMember->country->nami_id,
            'nachname' => $localMember->lastname,
            'ort' => $localMember->city,
            'plz' => $localMember->zip,
            'staatsangehoerigkeitId' => $localMember->nationality->id,
            'strasse' => $localMember->address,
            'wiederverwendenFlag' => $localMember->keepdata,
            'zeitschriftenversand' => $localMember->sendnewspaper
        ])->once();
        $this->app->instance(MemberReceiver::class, $receiver);

        $manager = app(MemberManager::class);

        $manager->push($localMember);
    }
}
