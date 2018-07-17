<?php

namespace Tests\Unit\Nami\Rules;

use App\Nami\Receiver\Group;
use App\Nami\Rules\ValidNamiCredentials;
use App\Nami\Service;
use Tests\Unit\NamiTestCase;
use \Mockery as M;

class CheckCredentialRuleTest extends NamiTestCase {
    /** @test*/
    public function it_returns_true_when_group_exists() {
        $group = M::mock(Group::class);
        $group->shouldReceive('all')->twice()->andReturn(
            collect(json_decode('[{"title": "Newgroup", "id": 1}]'))
        );
        $this->app->instance(Group::class, $group);

        $service = M::mock(Service::class);
        $service->shouldReceive('checkCredentials')->twice()->with('Tom', 'PW')->andReturn(true);
        $this->app->instance(Service::class, $service);

        $rule = new ValidNamiCredentials('Tom', 'PW', 1);
        $this->assertTrue($rule->passes('namiUser', 'Tom'));

        $rule = new ValidNamiCredentials('Tom', 'PW', 2);
        $this->assertFalse($rule->passes('namiUser', 'Tom'));
    }

    /** @test*/
    public function it_returns_true_when_credentials_are_valid() {
        $group = M::mock(Group::class);
        $group->shouldReceive('all')->once()->andReturn(
            collect(json_decode('[{"title": "Newgroup", "id": 1}]'))
        );
        $this->app->instance(Group::class, $group);

        $service = M::mock(Service::class);
        $service->shouldReceive('checkCredentials')->once()->with('Tom', 'correct')->andReturn(true);
        $service->shouldReceive('checkCredentials')->once()->with('Tom', 'wrong')->andReturn(false);
        $this->app->instance(Service::class, $service);

        $rule = new ValidNamiCredentials('Tom', 'correct', 1);
        $this->assertTrue($rule->passes('namiUser', 'Tom'));

        $rule = new ValidNamiCredentials('Tom', 'wrong', 1);
        $this->assertFalse($rule->passes('namiUser', 'Tom'));
    }
}
