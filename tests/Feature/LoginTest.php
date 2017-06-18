<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends \Tests\TestCase {
	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();
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
		$user = parent::createUser('passw');
		$this->post('/login', ['email' => $user->email, 'password' => 'passw'])->assertRedirect('/');

		$this->assertInstanceOf(\App\User::class, auth()->user());
	}
}
