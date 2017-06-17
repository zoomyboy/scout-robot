<?php

namespace Tests\Feature;

class LoginTest extends \Tests\TestCase {
	/** @test */
	public function it_redirects_to_login_page_if_not_logged_in() {
		$this->get('/')->assertRedirect('/login');
	}
}
