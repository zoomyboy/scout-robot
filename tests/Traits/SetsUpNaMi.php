<?php

namespace Tests\Traits;

use App\Facades\NaMi\NaMiMembership;
use Tests\Utilities\NaMiDatabaseMockService;
use App\Facades\NaMi\NaMi;

trait SetsUpNaMi {
	public function setUpNaMi($enabled = true) {
		$user = ($enabled) ? env('NAMI_TEST_USER') : '';
		$password = ($enabled) ? env('NAMI_TEST_PASSWORD') : '';
		$group = ($enabled) ? env('NAMI_TEST_GROUP') : '';

		$this->assertNotNull(\App\Conf::first(), 'You should run a conf seeder first.');

		\App\Conf::first()->update(['namiUser' => $user, 'namiPassword' => $password, 'namiEnabled' => $enabled, 'namiGroup' => $group]);

		NaMi::fake();
	}

}
