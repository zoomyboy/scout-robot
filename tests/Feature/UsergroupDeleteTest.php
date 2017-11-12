<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use App\Usergroup;
use App\Right;

class UsergroupDeleteTest extends TestCase {

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

		$this->runSeeder(\UsergroupSeeder::class, ['title' => 'NEW', 'rights' => [2,3]]);
	}

	/** @test */
	public function it_can_delete_a_usergroup() {
		$user = parent::auth('api');
		$this->deleteApi('usergroup/2')->assertSuccess();

		$usergroup = Usergroup::whereTitle('NEW')->first();
		$this->assertNull($usergroup);
	}

	/** @test */
	public function it_can_only_delete_usergroups_if_it_has_permission() {
		$user = parent::auth('api');

		$user->usergroup->rights()->detach(Right::where('key', 'usergroup')->first());

		$user = parent::auth('api');
		$this->deleteApi('usergroup/2')->assertForbidden();
	}

	/** @test */
	public function it_shouldnt_be_a_guest() {
		$this->deleteApi('usergroup/2')->assertUnauthorized();
	}

	/** @test */
	public function it_can_only_delete_a_usergroup_that_exists() {
		$user = parent::auth('api');
		$this->deleteApi('usergroup/200')->assertNotFound();
	}

	/** @test */
	public function it_can_only_delete_a_usergroup_that_has_no_members() {
		$user = parent::auth('api');

		$usergroup = $this->create('usergroup');
		$newUser = $this->create('user', ['usergroup_id' => $usergroup->id]);


		$this->deleteApi('usergroup/'.$usergroup->id)->assertValidationFailedWith('id', 'Diese Benutzergruppe hat noch einige Mitglieder. Du kannst sie daher erst löschen, wenn alle Benutzer aus der Gruppe entfernt wurden.');
	}
}
