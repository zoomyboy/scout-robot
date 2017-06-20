<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

	public function auth() {
		$this->be(\App\User::first());

		$this->assertInstanceOf(\App\User::class, auth()->user());
	}
}
