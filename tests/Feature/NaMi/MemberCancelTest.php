<?php

namespace Tests\Feature\Nami;

use Carbon\Carbon;
use \Mockery as M;
use App\Nami\Service;
use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberCancelTest extends FeatureTestCase
{
    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();
        $this->mockNamiUser(3);

        $this->authAsApi();
    }

    /** @test */
    public function it_cancels_the_member_when_keepdata_is_false() {
        $service = M::mock(Service::class);
        $service->shouldReceive('post')->with('https://nami.dpsg.de/ica/rest/nami/mitglied/filtered-for-navigation/mglschaft-beenden?gruppierung=3', [
            'id' => 2333,
            'isConfirmed' => true,
            'beendenZumDatum' => Carbon::now()->format('Y-m-d 00:00:00')
        ])->once()->andReturn(collect(json_decode('{"success":true,"data":null,"responseType":"OK","message":"Die Mitgliedschaft wurde beendet.","title":null}')));
        $this->app->instance(Service::class, $service);

        $this->fakeOnlineNamiMembers([
            ['id' => 2333, 'wiederverwendenFlag' => false]
        ]);

        $member = $this->create('Member', ['nami_id' => 2333, 'keepdata' => false]);

        $this->getApi("member/{$member->id}/cancel")
            ->assertSuccess();

        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }
}
