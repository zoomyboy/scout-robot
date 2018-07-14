<?php

namespace Tests\Unit;
use App\Nami\Interfaces\UserResolver;
use Tests\UnitTestCase;
use \Mockery as M;

abstract class NamiTestCase extends UnitTestCase {
    public function createNamiUser($user, $password, $group) {
        $resolver = M::mock(UserResolver::class);
        $resolver->shouldReceive('getUsername')->andReturn($user);
        $resolver->shouldReceive('getPassword')->andReturn($password);
        $resolver->shouldReceive('getGroup')->andReturn($group);
        $resolver->shouldReceive('hasCredentials')->andReturn(
            (bool)$user && (bool)$password
        );
        $this->app->instance(UserResolver::class, $resolver);

        return $resolver;
    }
}
