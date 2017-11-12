<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;

class EditProfileTest extends TestCase {

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
	public function a_user_can_edit_his_name_and_email() {
		$user = parent::auth('api');
		$this->patchApi('profile/'.$user->id, [
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
		parent::auth('api');
		$this->patchApi('profile/5', [
			'name' => 'Admin2',
			'email' => 'admin2@example.com'
		])->assertNotFound();

		$this->assertUserHasntChanged();
	}

	/** @test */
	public function it_can_only_edit_his_own_profile() {
		$user = parent::auth('api');

		$otherUser = $this->create('user');

		$this->patchApi('profile/'.$otherUser->id, [
			'name' => 'Admin3',
			'email' => 'admin3@example.com'
		])->assertForbidden();

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_shouldnt_be_a_guest() {
		$user = User::first();

		$this->patchApi('profile/'.$user->id, [
			'name' => 'Admin3',
			'email' => 'admin3@example.com'
		])->assertUnauthorized();

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_has_to_enter_a_valid_email_address() {
		$user = parent::auth('api');

		$this->patchApi('profile/'.$user->id, [
			'name' => 'Admin3',
			'email' => 'notaemail'
		])->assertValidationFailedWith('email', 'Das hier muss eine richtige E-Mail-Adresse sein.');

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_has_to_enter_a_name_and_an_email_address() {
		$user = parent::auth('api');

		$this->patchApi('profile/'.$user->id, [
			'name' => '',
			'email' => ''
		])->assertValidationFailedWith('email', 'Dieses Feld muss ausgefüllt werden.')->assertValidationFailedWith('name', 'Dieses Feld muss ausgefüllt werden.');

		$this->assertUserHasntChanged($user);
	}

	/** @test */
	public function it_cannot_edit_his_password() {
		$user = parent::auth('api');

		$this->patchApi('profile/'.$user->id, [
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
		$user = parent::auth('api');

		$this->patchApi('profile/'.$user->id, [
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
