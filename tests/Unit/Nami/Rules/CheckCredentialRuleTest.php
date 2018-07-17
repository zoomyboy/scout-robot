<?php

namespace Tests\Unit\Nami\Rules;

use App\Nami\Service;
use Tests\Unit\NamiTestCase;

class CheckCredentialRuleTest extends NamiTestCase {
    public function it_returns_true_when_nami_login_was_successful_and_the_group_is_readable() {
        $group = M::mock(Group::class);
        $group->shouldReceive('all')->once()->andReturn(collect(json_decode('[
            {"title": "Newgroup", "id": 1}
        ]')));
        $this->app->instance(Group::class, $group);

        $service = M::mock(Service::class);
        $service->shouldReceive('checkCredentials')->andReturn(true);
        $service->shouldReceive('login')->once()->andReturnNull();
        $this->app->instance(Service::class, $service);
    }
}
