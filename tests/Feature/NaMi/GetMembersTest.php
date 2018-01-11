<?php

namespace App\Feature\NaMi;

use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\NaMi\NaMiMember;
use App\Conf;
use App\Exceptions\NaMi\LoginException;

class GetMembersTest extends FeatureTestCase {

	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();

		@unlink(config('nami.cookie'));

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfSeeder');

		Conf::first()->update(['namiUser' => env('NAMI_TEST_USER')]);
		Conf::first()->update(['namiPassword' => env('NAMI_TEST_PASSWORD')]);
	}

	/** @test */
	public function it_receives_all_members_from_nami() {
		$list = NaMiMember::all(env('NAMI_TEST_GROUP'));
		$this->assertTrue($list->success);

		unlink(config('nami.cookie'));

		$list = NaMiMember::all(env('NAMI_TEST_GROUP'));
		$this->assertTrue($list->success);
	}

	/**
	 * @test
	 * @expectedException \App\Exceptions\NaMi\GroupAccessDeniedException
	 */
	public function it_throws_error_when_no_group_access() {
		$list = NaMiMember::all(env('NAMI_TEST_GROUP')+1);
		$this->assertTrue($list->success);
	}

	/**
	 * @test
	 * @expectedException \App\Exceptions\NaMi\LoginException
	 */
	public function it_throws_error_when_wrong_credentials() {
		Conf::first()->update(['namiPassword' => substr(env('NAMI_TEST_PASSWORD'),0,-1)]);

		$m = NaMiMember::all(env('NAMI_TEST_GROUP'));
	}
}
