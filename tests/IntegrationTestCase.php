<?php

namespace Tests;

use Zoomyboy\Tests\Traits\CreatesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;
use Zoomyboy\Tests\Traits\HandlesExceptions;
use Zoomyboy\Tests\Traits\AuthenticatesUsers;

class IntegrationTestCase extends \Tests\TestCase {
	use CreatesModels;
	use HandlesExceptions;
	use AuthenticatesUsers;

	public function setUp() {
		parent::setUp();
		$this->disableExceptionHandling();

		if(!property_exists($this, 'dontFakeNotifications')) {
			Notification::fake();
		}

		if(!property_exists($this, 'dontFakeEvents')) {
			Event::fake();
		}
	}

	public function afterAuthUserCreated($user) {
		\App\Right::get()->each(function($right) use ($user) {
			$user->usergroup->rights()->attach($right);
		});
	}
}
