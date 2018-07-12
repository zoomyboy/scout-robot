<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\Traits\MocksSetting;
use Tests\Traits\SetsUpNaMi;
use Zoomyboy\Tests\Traits\CreatesModels;
use Zoomyboy\Tests\Traits\FakesGuzzle;

abstract class UnitTestCase extends \Tests\TestCase {
    use MocksSetting;
    use FakesGuzzle;

	public function setUp() {
		parent::setUp();

		Notification::fake();
		Event::fake();
	}
}
