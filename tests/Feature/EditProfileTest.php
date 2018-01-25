<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;

class EditProfileTest extends FeatureTestCase {

	public function setUp() {
		parent::setUp();

		Config::set('seed.default_usergroup', 'SA');
		Config::set('seed.default_username', 'Admin');
		Config::set('seed.default_userpw', 'admin22');
		Config::set('seed.default_usermail', 'admin@example.tz');

		$this->runSeeder(\RightSeeder::class);
		$this->runSeeder(\UsergroupSeeder::class);
		$this->runSeeder(\UserSeeder::class);
	}

	/** @test */
	public function a_user_can_edit_his_name_and_email() {
		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/'.auth()->user()->id, [
			'name' => 'Admin2',
			'email' => 'admin2@example.com'
		])->assertSuccess();

		$user = User::find(auth()->guard('api')->user()->id);
		$this->assertEquals('Admin2', $user->name);
		$this->assertEquals('admin2@example.com', $user->email);
		$this->assertCanLogin('admin2@example.com', 'admin22');
	}

	/** @test */
	public function it_cannot_edit_a_user_that_doesnt_exist() {
		$this->withExceptionHandling();

		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/5', [
			'name' => 'Admin2',
			'email' => 'admin2@example.com'
		])->assertNotFound();

		$this->assertUserHasntChanged();
	}

	/** @test */
	public function it_can_only_edit_his_own_profile() {
		$this->withExceptionHandling();

		$this->be(\App\User::first(), 'api');

		$user = User::first();
		$otherUser = $this->create('user');

		$this->patchApi('profile/'.$otherUser->id, [
			'name' => 'Admin3',
			'email' => 'admin3@example.com'
		])->assertForbidden();

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_shouldnt_be_a_guest() {
		$this->withExceptionHandling();

		$user = User::first();

		$this->patchApi('profile/'.$user->id, [
			'name' => 'Admin3',
			'email' => 'admin3@example.com'
		])->assertUnauthorized();

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_has_to_enter_a_valid_email_address() {
		$this->withExceptionHandling();

		$user = User::first();

		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/'.auth()->user()->id, [
			'name' => 'Admin3',
			'email' => 'notaemail'
		])->assertValidationFailedWith('email');

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_has_to_enter_a_name_and_an_email_address() {
		$this->withExceptionHandling();

		$user = User::first();

		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/'.auth()->user()->id, [
			'name' => '',
			'email' => ''
		])
			->assertValidationFailedWith('email')
			->assertValidationFailedWith('name');

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_cannot_edit_his_password() {
		$this->withExceptionHandling();

		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/'.auth()->user()->id, [
			'name' => 'Test',
			'email' => 'test@example.com',
			'password' => 'abcdef',
			'password_confirmation' => 'abcdefg'
		])->assertSuccess();

		$this->assertCanLogin('test@example.com', 'admin22');
		$this->assertCannotLogin('test@example.com', 'abcdefg');
	}

	/** @test */
	public function it_cannot_edit_his_usergroup() {
		$this->withExceptionHandling();

		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/'.auth()->user()->id, [
			'name' => 'Test',
			'email' => 'test@example.com',
			'usergroup' => '2',
			'usergroup_id' => '2'
		])->assertSuccess();

		$user = User::find(auth()->guard('api')->user()->id);
		$this->assertEquals(1, $user->usergroup->id);
	}

	private function assertUserHasntChanged($user = null) {
		$user = ($user) ?: User::find(auth()->guard('api')->user()->id);
		$this->assertEquals('Admin', $user->name);
		$this->assertEquals('admin@example.tz', $user->email);

		return $user;
	}
}
