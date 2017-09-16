<?php

namespace Tests\ZFeature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;

class EditProfilePasswordTest extends TestCase {

	public function setUp() {
		parent::setUp();

		$this->runMigration('rights_table');
		$this->runMigration('usergroups_table');
		$this->runMigration('right_usergroup_table');
		$this->runMigration('users_table');

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
		$user = parent::auth('api');
		$this->patchApi('profile/'.$user->id.'/password', [
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg'
		])->assertSuccess();

		$this->assertCanLogin('admin@example.tz', 'abcdefg');
	}

	/** @test */
	public function it_cannot_edit_a_user_that_doesnt_exist() {
		parent::auth('api');
		$this->patchApi('profile/5/password', [
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg'
		])->assertNotFound();

		$this->assertUserHasntChanged();
	}

	/** @test */
	public function it_can_only_edit_his_own_profile() {
		$user = parent::auth('api');

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
		$user = User::first();

		$this->patchApi('profile/'.$user->id.'/password', [
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg'
		])->assertUnauthorized();

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_has_to_enter_a_valid_password() {
		$user = parent::auth('api');

		$this->patchApi('profile/'.$user->id.'/password', [
			'password' => '',
			'password_confirmation' => ''
		])
			->assertValidationFailedWith('password', 'Dieses Feld muss ausgefüllt werden.')
			->assertValidationFailedWith('password_confirmation', 'Dieses Feld muss ausgefüllt werden.');

		$this->patchApi('profile/'.$user->id.'/password', [
			'password' => 'abccdefg',
			'password_confirmation' => ''
		])
			->assertValidationFailedWith('password_confirmation', 'Dieses Feld muss ausgefüllt werden.');

		$this->patchApi('profile/'.$user->id.'/password', [
			'password' => '',
			'password_confirmation' => 'abcdefg'
		])
			->assertValidationFailedWith('password', 'Dieses Feld muss ausgefüllt werden.');

		$this->patchApi('profile/'.$user->id.'/password', [
			'password' => 'abcsvdsvdsv',
			'password_confirmation' => 'abcdef'
		])
			->assertValidationFailedWith('password', 'Die beiden Passwörter stimmen nicht überein.');

		$this->patchApi('profile/'.$user->id.'/password', [
			'password' => 'abc',
			'password_confirmation' => 'abc'
		])
			->assertValidationFailedWith('password', 'Hier müssen mindestens 6 Zeichen eingegeben werden.');

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_cannot_edit_his_email() {
		$user = parent::auth('api');

		$this->patchApi('profile/'.$user->id.'/password', [
			'name' => 'Test',
			'email' => 'test@example.com',
			'password' => 'abcdefg',
			'password_confirmation' => 'abcdefg',
			'usergroup' => '2',
			'usergroup_id' => '2'
		])->assertSuccess();

		$this->assertCanLogin('admin@example.tz', 'abcdefg');

		$user = User::find($user->id);
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
