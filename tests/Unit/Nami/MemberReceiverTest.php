<?php

namespace Tests\Unit\Nami;

use App\Nami\Interfaces\UserResolver;
use App\Nami\Receiver\Member;
use App\Nami\Service;
use GuzzleHttp\Psr7\Response;
use Tests\UnitTestCase;
use \Mockery as M;

class MemberReceiverTest extends UnitTestCase {
    public function setUp() {
        parent::setUp();

        $resolver = M::mock(UserResolver::class);
        $resolver->shouldReceive('getGroup')->andReturn(3);
        $this->app->instance(UserResolver::class, $resolver);
    }

    /** @test */
    public function it_gets_all_member_ids() {
        $service = M::mock(Service::class);
        $service->shouldReceive('get')->with('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/3/flist')->once()
            ->andReturn(collect(json_decode('{"success":true,"data":[{"id":2334}]}')));
        $this->app->instance(Service::class, $service);

        $all = app(Member::class)->all();
        $this->assertEquals(2334, $all->first()->id);
    }

    /** @test */
    public function it_gets_a_single_member() {
        $service = M::mock(Service::class);
        $service->shouldReceive('get')->with('/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/3/2334')->once()
            ->andReturn(
                collect(json_decode('{"success":true,"data":{"id":2334,"geschlechtId": 55}}'))
            );
        $this->app->instance(Service::class, $service);

        $member = app(Member::class)->single(2334);
        $this->assertEquals(2334, $member->id);
        $this->assertEquals(55, $member->geschlechtId);
    }

    /** @test */
    public function it_updates_a_single_member() {
        $service = M::mock(Service::class);
        $service->shouldReceive('put')->with(
            '/ica/rest/nami/mitglied/filtered-for-navigation/gruppierung/gruppierung/3/2442',
            ['vorname' => 'Pille']
        )->once()
            ->andReturn(
                collect(json_decode('{"success":true,"data":{"vorname":"Pille", "id": 2442}}'))
            );
        $this->app->instance(Service::class, $service);

        $member = app(Member::class)->update(2442, ['vorname' => 'Pille']);
        $this->assertEquals(2442, $member->id);
        $this->assertEquals("Pille", $member->vorname);
    }
}
