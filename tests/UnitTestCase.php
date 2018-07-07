<?php

namespace Tests;

use Tests\Traits\SetsUpNaMi;
use Zoomyboy\Tests\Traits\CreatesModels;
use Zoomyboy\Tests\Traits\HandlesExceptions;
use Zoomyboy\Tests\Traits\AuthenticatesUsers;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Traits\MocksSetting;

class UnitTestCase extends \Tests\TestCase {
    use MocksSetting;

	public function setUp() {
		parent::setUp();

		Notification::fake();
		Event::fake();
	}

	public function afterAuthUserCreated($user) {
		\App\Right::get()->each(function($right) use ($user) {
			$user->usergroup->rights()->attach($right);
		});
	}
}
