<?php

namespace Tests;

use Zoomyboy\Tests\Traits\CreatesModels;
use Zoomyboy\Tests\Traits\HandlesExceptions;
use Zoomyboy\Tests\Traits\AuthenticatesUsers;
use Zoomyboy\Tests\Traits\HandlesApiCalls;
use Zoomyboy\Tests\Traits\TestsEmails;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FeatureTestCase extends \Tests\TestCase {
	use CreatesModels;
	use HandlesExceptions;
	use AuthenticatesUsers;
	use HandlesApiCalls;
	use TestsEmails;
	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();
		$this->disableExceptionHandling();
	}
}
