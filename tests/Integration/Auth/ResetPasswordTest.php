<?php

namespace Tests\Integration\Auth;

use App\Usergroup;
use Tests\IntegrationTestCase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordResetNotification;

class ResetPasswordTest extends IntegrationTestCase {
	public function setUp() {
		parent::setUp();

        factory(Usergroup::class)->create();
	}

	/** @test */
	public function it_sends_a_password_reset_notification_when_reset_password_requested() {
		$user = $this->create('User');

		$this->post('/password/email', ['email' => $user->email])
			->assertSuccess();

		Notification::assertSentTo($user, PasswordResetNotification::class, function($n) use ($user) {
			return $n->user->id == $user->id;
		});
	}
}
