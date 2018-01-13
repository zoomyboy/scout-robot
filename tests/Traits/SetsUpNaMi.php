<?php

namespace Tests\Traits;

trait SetsUpNaMi {
	public function setUpNaMi() {
		\App\Conf::first()->update(['namiUser' => env('NAMI_TEST_USER'), 'namiPassword' => env('NAMI_TEST_PASSWORD'), 'namiEnabled' => true, 'namiGroup' => env('NAMI_TEST_GROUP')]);
	}

	public function mockSyncNaMi() {
		$this->runSeeder('ConfessionMockSeeder');
	}
}
