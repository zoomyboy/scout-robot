<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\MigratesDb;
use Tests\Traits\CreatesModels;
use Tests\Helpers\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
	use MigratesDb;
	use CreatesModels;

	public function assertCanLogin($email, $password, $guard = 'web') {
		$this->assertTrue(Auth::guard($guard)->attempt(['email' => $email, 'password' => $password]));
	}

	public function assertCannotLogin($email, $password, $guard = 'web') {
		$this->assertFalse(Auth::guard($guard)->attempt(['email' => $email, 'password' => $password]));
	}

	public function assertDate($date) {
		return $this->assertInstanceOf(Carbon::class, $date);
	}

	public function assertDateBefore($date, $date2) {
		return $this->assertTrue($date->gt($date2), 'Failed asserting that '.$date.' is before '.$date2);
	}

	public function assertEmail($email) {
		return $this->assertEquals($email, filter_var($email, FILTER_VALIDATE_EMAIL), $email.' is not an email.');
	}

	public function assertPhone($phone) {
		return $this->assertRegExp('/\+[0-9]{2} [0-9]{3} [0-9]{5,7}/', $phone);
	}
}
