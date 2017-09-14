<?php

namespace Tests\Unit\Seeder;

use Tests\TestCase;
use \App\Usergroup as Model;
use \App\Right;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsergroupTtest extends TestCase
{
	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();
		$seeder = new \RightSeeder();
		$seeder->run();
	}

	/** @test */
	public function it_seeds_a_default_user_group() {
		$seeder = new \UsergroupSeeder();
		$seeder->run();
		$model = Model::where('title', 'Super-Administrator')->first();
		$this->assertNotNull($model);
	}

	/** @test */
	public function it_seeds_a_default_user_group_as_default() {
		$seeder = new \UsergroupSeeder();
		$seeder->run();

		$this->assertNotNull(\UsergroupSeeder::default());
		$this->assertEquals('Super-Administrator', \UsergroupSeeder::default()->title);
	}

	/** @test */
	public function it_seeds_a_default_user_group_and_assigns_all_rights() {
		$seeder = new \UsergroupSeeder();
		$seeder->run();

		$model = Model::where('title', 'Super-Administrator')->first();

		$this->assertEquals(Right::get()->count(), $model->rights->count());
	}
}
