<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\MigratesDb;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
	use MigratesDb;

	public function auth() {
		$this->be(\App\User::first());

		$this->assertInstanceOf(\App\User::class, auth()->user());
	}
}
