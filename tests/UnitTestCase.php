<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\Traits\MocksSetting;
use Tests\Traits\SetsUpNaMi;
use Zoomyboy\Tests\Traits\CreatesModels;
use Zoomyboy\Tests\Traits\FakesGuzzle;

class UnitTestCase extends \Tests\TestCase {
    use MocksSetting;
    use FakesGuzzle;
    use CreatesModels; /** @todo rausnehmen */
    use DatabaseMigrations; /** @todo rausnehmen */
    use SetsUpNaMi;  /** @todo rausnehmen */

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
