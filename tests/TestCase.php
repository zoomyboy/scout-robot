<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\MigratesDb;
use Tests\Traits\CreatesModels;
use Tests\Helpers\Response;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
	use MigratesDb;
	use CreatesModels;

	public function auth($guard = 'web') {
		$this->be(\App\User::first(), $guard);

		$this->assertInstanceOf(\App\User::class, auth()->guard($guard)->user());

		return auth()->guard($guard)->user();
	}

	public function postApi($to, $data=[]) {
		return $this->postJson('/api/'.$to, $data);
	}

	public function deleteApi($to, $data=[]) {
		return $this->deleteJson('/api/'.$to, $data);
	}

	public function patchApi($to, $data) {
		return $this->postJson('/api/'.$to, array_merge($data, ['_method' => 'patch']));
	}

	public function assertCanLogin($email, $password, $guard = 'web') {
		$this->assertTrue(Auth::guard($guard)->attempt(['email' => $email, 'password' => $password]));
	}

	public function assertCannotLogin($email, $password, $guard = 'web') {
		$this->assertFalse(Auth::guard($guard)->attempt(['email' => $email, 'password' => $password]));
	}

    /**
     * Create the test response instance from the given response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function createTestResponse($response)
    {
        return Response::fromBaseResponse($response);
    }
}
