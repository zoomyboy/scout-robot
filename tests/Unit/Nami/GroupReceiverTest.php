<?php

namespace Tests\Unit\Nami;

use App\Nami\Receiver\Group;
use App\Nami\Service;
use Tests\Unit\NamiTestCase;
use \Mockery as M;

class GroupReceiverTest extends NamiTestCase {
	public function setUp() {
		parent::setUp();

        $this->createNamiUser('Philipp', 'frr', 3);
	}

	/** @test */
	public function it_receives_all_groups_from_nami() {
        $service = M::mock(Service::class);
        $service->shouldReceive('get')->with('/ica/rest/nami/gruppierungen/filtered-for-navigation/gruppierung/node/root')
            ->andReturn(collect(json_decode('{"success":true,"data":[{
                "descriptor":"SG Wald",
                "id": 1002
            }]}')));
        $this->app->instance(Service::class, $service);

        $all = app(Group::class)->all();
        $this->assertEquals(1002, $all->first()->id);
        $this->assertEquals("SG Wald", $all->first()->title);
    }
}
