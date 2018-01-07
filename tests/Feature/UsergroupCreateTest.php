<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use App\Usergroup;
use App\Right;
use Tests\FeatureTestCase;

class UsergroupCreateTest extends FeatureTestCase {

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
	public function it_can_create_a_usergroup() {
		$this->withExceptionHandling();
		$this->authAsApi();

		$this->postApi('usergroup', [
			'title' => 'abcdefg',
			'rights' => [2, 3]
		])->assertSuccess();

		$usergroup = Usergroup::whereTitle('abcdefg')->first();
		$this->assertNotNull($usergroup);
		$this->assertEquals([2, 3], $usergroup->rights->pluck('id')->toArray());

		$this->postApi('usergroup', [
			'title' => 'abcdefgh',
			'rights' => []
		])->assertSuccess();

		$usergroup = Usergroup::whereTitle('abcdefgh')->first();
		$this->assertNotNull($usergroup);
		$this->assertEquals([], $usergroup->rights->pluck('id')->toArray());
	}

	/** @test */
	public function it_can_only_create_usergroups_if_it_has_permission() {
		$this->withExceptionHandling();
		$this->authAsApi();

		auth()->user()->usergroup->rights()->detach(Right::where('key', 'usergroup')->first());

		$this->postApi('usergroup', [
			'title' => 'abcdefg',
			'rights' => [2, 3]
		])->assertForbidden();
	}

	/** @test */
	public function it_shouldnt_be_a_guest() {
		$this->withExceptionHandling();

		$user = User::first();

		$this->postApi('usergroup', [
			'title' => 'abcdefg',
			'rights' => [2, 3]
		])->assertUnauthorized();
	}

	/** @test */
	public function it_has_to_enter_a_name() {
		$this->withExceptionHandling();
		$this->authAsApi();

		$this->postApi('usergroup', [
			'title' => '',
			'rights' => [2, 3]
		])->assertValidationFailedWith('title');
	}

	/** @test */
	public function it_has_to_enter_a_valid_right() {
		$this->withExceptionHandling();
		$this->authAsApi();

		$this->postApi('usergroup', [
			'title' => 'Abc',
			'rights' => [3, 999]
		])->assertValidationFailedWith('rights.1');
	}
}
