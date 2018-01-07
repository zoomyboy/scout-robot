<?php

namespace Tests;

use Zoomyboy\Tests\Traits\CreatesModels;
use Zoomyboy\Tests\Traits\HandlesExceptions;
use Zoomyboy\Tests\Traits\AuthenticatesUsers;
use Zoomyboy\Tests\Traits\HandlesApiCalls;

class FeatureTestCase extends \Tests\TestCase {
	use CreatesModels;
	use HandlesExceptions;
	use AuthenticatesUsers;
	use HandlesApiCalls;

	public function setUp() {
		parent::setUp();
		$this->disableExceptionHandling();
	}
}
