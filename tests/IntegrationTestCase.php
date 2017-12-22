<?php

namespace Tests;

use Zoomyboy\Tests\Traits\CreatesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;
use Zoomyboy\Tests\Traits\HandlesExceptions;

class IntegrationTestCase extends \Tests\TestCase {
	use CreatesModels;
	use HandlesExceptions;

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
}
