<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\MigratesDb;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
	use MigratesDb;

	public function auth($guard = 'web') {
		$this->be(\App\User::first(), $guard);

		$this->assertInstanceOf(\App\User::class, auth()->guard($guard)->user());
	}
}
