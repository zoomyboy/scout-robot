<?php

namespace App\Feature\NaMi;

use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\NaMi\NaMiMember;
use App\Conf;

class GetMembersTest extends FeatureTestCase {

	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfSeeder');

		Conf::first()->update(['namiUser' => env('NAMI_TEST_USER')]);
		Conf::first()->update(['namiPassword' => env('NAMI_TEST_PASSWORD')]);
	}

	/** @test */
	public function it_receives_all_members_from_nami() {
		$list = NaMiMember::all();
		$this->assertTrue($list->success);
	}
}
