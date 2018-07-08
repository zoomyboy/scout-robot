<?php

namespace Tests;

use Zoomyboy\Tests\Traits\FakesGuzzle;
use Tests\Traits\MocksSetting;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;

class UnitTestCase extends \Tests\TestCase {
    use MocksSetting;
    use FakesGuzzle;

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
