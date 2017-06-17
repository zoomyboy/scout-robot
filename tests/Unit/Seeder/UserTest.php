<?php

namespace Tests\Unit\Seeder;

use Tests\TestCase;
use \App\User as Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class User extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function it_seeds_a_user() {
		$seeder = new \UserSeeder();
		$seeder->run();
		$model = Model::where('name', 'Administrator')->first();
		$this->assertNotNull($model);
		$this->assertEquals('admin@example.com', $model->email);
		$this->assertEquals('Administrator', $model->name);

		//Check if we can login
		$this->assertTrue(auth()->attempt(['email' => $model->email, 'password' => 'admin']));
	}
}
