<?php

namespace Tests;

use Zoomyboy\Tests\Traits\CreatesModels;
use Zoomyboy\Tests\Traits\HandlesExceptions;
use Zoomyboy\Tests\Traits\AuthenticatesUsers;

class FeatureTestCase extends \Tests\TestCase {
	use CreatesModels;
	use HandlesExceptions;
	use AuthenticatesUsers;

	public function setUp() {
		parent::setUp();
		$this->disableExceptionHandling();
	}
}
