<?php

namespace App\Feature\NaMi;

use Tests\FeatureTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Facades\NaMi\NaMiGroup;
use App\Conf;
use App\Exceptions\NaMi\LoginException;
use Tests\Traits\SetsUpNaMi;

class GetGroupsTest extends FeatureTestCase {

	use DatabaseMigrations;
	use SetsUpNaMi;

	public function setUp() {
		parent::setUp();

		@unlink(config('nami.cookie'));

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfSeeder');

		$this->SetUpNaMi();
	}

	/** @test */
	public function it_receives_all_groups_from_nami() {
		$list = NaMiGroup::all();
		$this->assertEquals(env('NAMI_TEST_GROUP'), $list[0]->id);
	}
}
