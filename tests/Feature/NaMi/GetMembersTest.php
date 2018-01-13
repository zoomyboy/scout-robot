<?php

namespace App\Feature\NaMi;

use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\NaMi\NaMiMember;
use App\Conf;
use App\Exceptions\NaMi\LoginException;
use Tests\Traits\SetsUpNaMi;

class GetMembersTest extends FeatureTestCase {

	use DatabaseMigrations;
	use SetsUpNaMi;

	public function setUp() {
		parent::setUp();

		@unlink(config('nami.cookie'));

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfSeeder');

		$this->setUpNaMi();
	}

	/** @test */
	public function it_receives_all_members_from_nami() {
		$list = NaMiMember::all();
	}

	/**
	 * @test
	 * @expectedException \App\Exceptions\NaMi\GroupException
	 */
	public function it_throws_error_when_no_group_access() {
		\App\Conf::first()->update(['namiGroup' => '12345']);
		NaMiMember::all();
	}

	/**
	 * @test
	 * @expectedException \App\Exceptions\NaMi\LoginException
	 */
	public function it_throws_error_when_wrong_credentials() {
		Conf::first()->update(['namiPassword' => substr(env('NAMI_TEST_PASSWORD'),0,-1)]);

		NaMiMember::all();
	}
}
