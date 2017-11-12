<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use App\Usergroup;
use App\Right;

class UsergroupEditTest extends TestCase {

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
	public function it_can_edit_a_usergroup() {
		$user = parent::auth('api');
		$this->patchApi('usergroup/2', [
			'title' => 'TZTZ',
			'rights' => [2, 3, 4]
		])->assertSuccess();

		$usergroup = Usergroup::whereTitle('TZTZ')->first();
		$this->assertNotNull($usergroup);
		$this->assertEquals([2, 3, 4], $usergroup->rights->pluck('id')->toArray());

		$this->patchApi('usergroup/2', [
			'title' => 'NEW',
			'rights' => []
		])->assertSuccess();

		$usergroup = Usergroup::whereTitle('NEW')->first();
		$this->assertNotNull($usergroup);
		$this->assertEquals([], $usergroup->rights->pluck('id')->toArray());
	}

	/** @test */
	public function it_can_only_edit_usergroups_if_it_has_permission() {
		$user = parent::auth('api');

		$user->usergroup->rights()->detach(Right::where('key', 'usergroup')->first());

		$this->patchApi('usergroup/2', [
			'title' => 'NEW',
			'rights' => [2]
		])->assertForbidden();
	}

	/** @test */
	public function it_shouldnt_be_a_guest() {
		$this->patchApi('usergroup/2', [
			'title' => 'NEW',
			'rights' => [2]
		])->assertUnauthorized();
	}

	/** @test */
	public function it_has_to_enter_a_name() {
		$user = parent::auth('api');
		$this->patchApi('usergroup/2', [
			'title' => '',
			'rights' => [2]
		])->assertValidationFailedWith('title', 'Dieses Feld muss ausgefüllt werden.');
	}

	/** @test */
	public function it_has_to_enter_a_valid_right() {
		$user = parent::auth('api');
		$this->patchApi('usergroup/2', [
			'title' => 'FGFG',
			'rights' => [2, 999]
		])->assertValidationFailedWith('rights.1', 'Dieses Recht existiert nicht.');
	}
}
