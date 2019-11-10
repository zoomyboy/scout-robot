<?php

namespace Tests;

use Carbon\Carbon;
use Tests\Helpers\Response;
use Tests\Traits\FakesNaMi;
use Tests\Traits\MigratesDb;
use Tests\Traits\CreatesModels;
use Tests\Traits\CreatesPayments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Zoomyboy\Tests\Traits\AuthenticatesUsers;
use Zoomyboy\Tests\Traits\HandlesApiCalls;

abstract class TestCase extends BaseTestCase
{
    public $apiPrefix = '';

    use CreatesApplication;
    use MigratesDb;
    use CreatesModels;
    use FakesNaMi;
    use CreatesPayments;
    use AuthenticatesUsers;
    use HandlesApiCalls;
    
    public function setUp() {
        parent::setUp();

        $this->withoutExceptionHandling();

        Config::set('seed.default_usergroup', 'NewUserGroup');
        Config::set('seed.default_username', 'Admin');
        Config::set('seed.default_userpw', 'admin22');
        Config::set('seed.default_usermail', 'admin@example.tz');
        Config::set('seed.default_country', 'Algerien');

        $this->seed(\DatabaseSeeder::class);
    }

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
