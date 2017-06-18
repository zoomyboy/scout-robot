<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

	public function auth() {
		$this->be($this->createUser('examplepassword'));

		$this->assertInstanceOf(\App\User::class, auth()->user());
	}

	public function createUser($password) {
		return factory(\App\User::class)->create(['password' => bcrypt($password)]);
	}
}
