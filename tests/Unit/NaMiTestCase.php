<?php

namespace Tests\Unit;
use App\NaMi\Interfaces\UserResolver;
use Tests\UnitTestCase;
use \Mockery as M;

abstract class NaMiTestCase extends UnitTestCase {
    public function createNamiUser($user, $password) {
        $resolver = M::mock(UserResolver::class);
        $resolver->shouldReceive('getUsername')->andReturn($user);
        $resolver->shouldReceive('getPassword')->andReturn($password);
        $resolver->shouldReceive('hasCredentials')->andReturn(
            (bool)$user && (bool)$password
        );
        $this->app->instance(UserResolver::class, $resolver);

        return $resolver;
    }
}
