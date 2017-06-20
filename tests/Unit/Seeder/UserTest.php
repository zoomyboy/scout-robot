<?php

namespace Tests\Unit\Seeder;

use Tests\TestCase;
use \App\User as Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();
		(new \RightSeeder)->run();
		(new \UsergroupSeeder)->run();

		$seeder = new \UserSeeder();
		$seeder->run();
	}

	/** @test */
	public function it_seeds_a_user_with_properties() {
		$model = Model::where('name', 'Administrator')->first();
		$this->assertNotNull($model);
		$this->assertEquals('admin@example.com', $model->email);
		$this->assertEquals('Administrator', $model->name);
	}

	/** @test */
	public function it_seeds_a_user_and_loggs_in() {
		$model = Model::where('name', 'Administrator')->first();

		$this->assertTrue(auth()->attempt(['email' => $model->email, 'password' => 'admin']));
	}

	/** @test */
	public function it_seeds_a_user_and_assigns_default_group() {
		$model = Model::where('name', 'Administrator')->first();

		$this->assertNotNull($model->usergroup);

		$this->assertEquals('Super-Administrator', $model->usergroup->title);
	}
}
