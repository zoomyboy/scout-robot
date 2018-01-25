<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Tests\FeatureTestCase;

class EditProfilePasswordTest extends FeatureTestCase {

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
	public function a_user_can_edit_his_password() {
		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/'.auth()->user()->id.'/password', [
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg'
		])->assertSuccess();

		$this->assertCanLogin('admin@example.tz', 'abcdefg');
	}

	/** @test */
	public function it_cannot_edit_a_user_that_doesnt_exist() {
		$this->withExceptionHandling();

		$this->be(\App\User::first(), 'api');
		
		$this->patchApi('profile/5/password', [
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg'
		])->assertNotFound();

		$this->assertUserHasntChanged();
	}

	/** @test */
	public function it_can_only_edit_his_own_profile() {
		$this->withExceptionHandling();

		$this->be(\App\User::first(), 'api');

		$otherUser = $this->create('user');
		$oldUserPassword = $otherUser->password;

		$this->patchApi('profile/'.$otherUser->id.'/password', [
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg'
		])->assertForbidden();

		$otherUser = User::find($otherUser->id);
		$this->assertEquals($oldUserPassword, $otherUser->password);
	}

	/** @test */
	public function it_shouldnt_be_a_guest() {
		$this->withExceptionHandling();

		$user = User::first();

		$this->patchApi('profile/'.$user->id.'/password', [
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg'
		])->assertUnauthorized();

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_has_to_enter_a_valid_password() {
		$this->withExceptionHandling();

		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/'.auth()->user()->id.'/password', [
			'password' => '',
			'password_confirmation' => ''
		])
			->assertValidationFailedWith('password')
			->assertValidationFailedWith('password_confirmation');

		$this->patchApi('profile/'.auth()->user()->id.'/password', [
			'password' => 'abccdefg',
			'password_confirmation' => ''
		])
			->assertValidationFailedWith('password_confirmation');

		$this->patchApi('profile/'.auth()->user()->id.'/password', [
			'password' => '',
			'password_confirmation' => 'abcdefg'
		])
			->assertValidationFailedWith('password');

		$this->patchApi('profile/'.auth()->user()->id.'/password', [
			'password' => 'abcsvdsvdsv',
			'password_confirmation' => 'abcdef'
		])
			->assertValidationFailedWith('password');

		$this->patchApi('profile/'.auth()->user()->id.'/password', [
			'password' => 'abc',
			'password_confirmation' => 'abc'
		])
			->assertValidationFailedWith('password');

		$this->assertUserHasntChanged(auth()->user());
	}

	/** @test */
	public function it_cannot_edit_his_email() {
		$this->withExceptionHandling();

		$this->be(\App\User::first(), 'api');

		$this->patchApi('profile/'.auth()->user()->id.'/password', [
			'name' => 'Test',
			'email' => 'test@example.com',
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg',
			'usergroup' => '2',
			'usergroup_id' => '2'
		])->assertSuccess();

		$this->assertCanLogin('admin@example.tz', 'abcdefg');

		$user = User::find(auth()->user()->id);
		$this->assertEquals('Admin', $user->name);
		$this->assertEquals(1, $user->usergroup->id);

	}

	private function assertUserHasntChanged($user = null) {
		$user = ($user) ?: User::find(auth()->guard('api')->user()->id);
		$this->assertEquals('Admin', $user->name);
		$this->assertEquals('admin@example.tz', $user->email);
		$this->assertCanLogin('admin@example.tz', 'admin22');

		return $user;
	}
}
