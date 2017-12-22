<?php

namespace Tests\Integration\Auth;

use Tests\IntegrationTestCase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordResetNotification;

class ResetPasswordTest extends IntegrationTestCase {
	public function setUp() {
		parent::setUp();

		$this->runMigration('users_table');
		$this->runMigration('usergroups_table');
		$this->runMigration('rights_table');
		$this->runMigration('right_usergroup_table');
		$this->runMigration('password_resets_table');
		$this->runMigration('images_table');

		$this->runSeeder('UsergroupSeeder');
	}

	/** @test */
	public function it_resets_a_password_of_a_user() {
		$user = $this->create('User');

		$this->post('/password/email', ['email' => $user->email])
			->assertSuccess();

		Notification::assertSentTo($user, PasswordResetNotification::class, function($n) use ($user) {
			return $n->user->id == $user->id;
		});
	}
}
