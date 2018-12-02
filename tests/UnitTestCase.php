<?php

namespace Tests;

use Tests\Traits\SetsUpNaMi;
use Tests\Traits\MocksSetting;
use Tests\Traits\SeedsDatabase;
use Illuminate\Support\Facades\Event;
use Zoomyboy\Tests\Traits\FakesGuzzle;
use Zoomyboy\Tests\Traits\CreatesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

abstract class UnitTestCase extends \Tests\TestCase {
    use MocksSetting;
    use FakesGuzzle;
    use SeedsDatabase;

	public function setUp() {
		parent::setUp();

		Notification::fake();
		Event::fake();
	}
}
