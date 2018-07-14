<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Zoomyboy\Tests\Traits\AuthenticatesUsers;
use Zoomyboy\Tests\Traits\CreatesModels;
use Zoomyboy\Tests\Traits\FakesGuzzle;
use Zoomyboy\Tests\Traits\HandlesApiCalls;
use Zoomyboy\Tests\Traits\HandlesExceptions;
use Zoomyboy\Tests\Traits\TestsEmails;

class FeatureTestCase extends \Tests\TestCase {
	use CreatesModels;
	use HandlesExceptions;
	use AuthenticatesUsers;
	use HandlesApiCalls;
	use TestsEmails;
    use FakesGuzzle;
	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();
		$this->disableExceptionHandling();
	}
}
