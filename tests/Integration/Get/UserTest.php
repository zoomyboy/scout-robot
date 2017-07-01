<?php

namespace Tests\Integration\Get;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends \Tests\TestCase {

	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();

		(new \RightSeeder)->run();
		(new \UsergroupSeeder)->run();
		(new \UserSeeder)->run();
	}

	/** @test */
	public function it_gets_a_users_properties_via_json() {
		parent::auth();
		$response = $this->get('/api/authuser');
		$response->assertStatus(200);
		$response->assertJson(['name' => 'Administrator']);
		$response->assertJsonStructure(['usergroup' => []]);
		$response->assertJsonStructure(['usergroup' => ['rights' => []]]);
	}
}
