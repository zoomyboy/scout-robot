<?php

namespace Tests\Helpers;

use Illuminate\Foundation\Testing\TestResponse;

class Response extends TestResponse {

	use \Illuminate\Validation\Concerns\FormatsMessages;

	public $customMessages = [];

	public function assertSuccess() {
		return $this->assertStatus(200);
	}

	public function assertNotFound() {
		return $this->assertStatus(404);
	}

	public function assertForbidden() {
		return $this->assertStatus(403);
	}

	public function assertUnauthorized() {
		return $this->assertStatus(401);
	}

	public function assertValidationFailedWith($field, $value) {
		return $this->assertJson([$field => [$value]])->assertStatus(422);
	}
}
