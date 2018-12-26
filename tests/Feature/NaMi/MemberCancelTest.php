<?php

namespace Tests\Feature\Nami;

use Carbon\Carbon;
use \Mockery as M;
use App\Nami\Service;
use Tests\FeatureTestCase;
use App\Events\MemberCancelled;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberCancelTest extends FeatureTestCase
{
    public function setUp() {
        parent::setUp();

        $this->setupNamiDatabaseModels();
        $this->mockNamiUser(3);

        $this->authAsApi();

        Event::fake(MemberCancelled::class);
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

        Event::assertDispatched(MemberCancelled::class, function($e) use ($member) {
            return $e->memberId == $member->id;
        });
    }

    /** @test */
    public function it_only_deletes_a_member_when_it_is_not_synched_with_nami() {
        $service = M::mock(Service::class);
        $service->shouldReceive('post')->withArgs(function($url) {
            return strpos('beenden', $url) !== false;
        })
        ->never();
        $this->app->instance(Service::class, $service);

        $member = $this->create('Member', ['nami_id' => null, 'keepdata' => false]);

        $this->getApi("member/{$member->id}/cancel")
            ->assertSuccess();

        $this->assertDatabaseMissing('members', ['id' => $member->id]);

        Event::assertDispatched(MemberCancelled::class, function($e) use ($member) {
            return $e->memberId == $member->id;
        });
    }

    /** @test */
    public function it_also_deletes_the_payments() {
        $service = M::mock(Service::class);
        $this->app->instance(Service::class, $service);

        $member = $this->create('Member', ['nami_id' => null, 'keepdata' => false]);
        $this->createPayment($member, 'Bezahlt', 'Voll', 2018);

        $this->getApi("member/{$member->id}/cancel")
            ->assertSuccess();

        $this->assertDatabaseMissing('members', ['id' => $member->id]);
        $this->assertDatabaseMissing('payments', ['member_id' => $member->id]);

        Event::assertDispatched(MemberCancelled::class, function($e) use ($member) {
            return $e->memberId == $member->id;
        });
    }
}
