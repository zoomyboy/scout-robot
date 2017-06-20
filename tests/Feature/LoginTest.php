<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use \App\User;

class LoginTest extends \Tests\TestCase {
	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();
		(new \RightSeeder)->run();
		(new \UsergroupSeeder)->run();
		(new \UserSeeder)->run();
	}

	/** @test */
	public function it_redirects_to_login_page_if_not_logged_in() {
		$this->get('/')->assertRedirect('/login');
	}

	/** @test */
	public function it_redirects_to_home_page_if_logged_in() {
		parent::auth();
		$this->get('/login')->assertRedirect('/');
	}

	/** @test */
	public function it_loggs_in_successfully() {
		$user = User::first();
		$this->post('/login', ['email' => $user->email, 'password' => 'admin'])->assertRedirect('/');

		$this->assertInstanceOf(\App\User::class, auth()->user());
	}
}
