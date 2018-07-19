<?php

namespace Tests\Unit\Nami;

use App\Nami\Receiver\Membership;
use App\Nami\Service;
use GuzzleHttp\Psr7\Response;
use Tests\UnitTestCase;
use \Mockery as M;

class MembershipReceiverTest extends UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    /** @test */
    public function it_gets_all_membership_ids() {
        $service = M::mock(Service::class);
        $service->shouldReceive('get')->with('/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/5')
            ->once()
            ->andReturn(collect(json_decode('{"success":true,"data":[{"id":2334}]}')));
        $this->app->instance(Service::class, $service);

        $all = app(Membership::class)->all(5);
        $this->assertEquals(2334, $all->first()->id);
    }

    /** @test */
    public function it_gets_a_single_membership() {
        $service = M::mock(Service::class);
        $service->shouldReceive('get')->with('/ica/rest/nami/zugeordnete-taetigkeiten/filtered-for-navigation/gruppierung-mitglied/mitglied/5/445')
            ->andReturn(
                collect(json_decode('{"success":true,"data":{"id":2334,"taetigkeitId": 55}}'))
            );
        $this->app->instance(Service::class, $service);

        $membership = app(Membership::class)->single(5, 445);
        $this->assertEquals(2334, $membership->id);
        $this->assertEquals(55, $membership->taetigkeitId);
    }
}
