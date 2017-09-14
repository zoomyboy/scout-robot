<?php

namespace Tests\Unit\Seeder;

use Tests\TestCase;
use \App\User as Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
	public function setUp() {
		parent::setUp();

		Config::set('seed.default_usergroup', 'SA');
		Config::set('seed.default_username', 'Admin');
		Config::set('seed.default_userpw', 'admin22');
		Config::set('seed.default_usermail', 'admin@example.tz');

		$this->runMigration('rights_table');
		$this->runMigration('usergroups_table');
		$this->runMigration('right_usergroup_table');
		$this->runMigration('users_table');

		$this->runSeeder(\RightSeeder::class);
		$this->runSeeder(\UsergroupSeeder::class);
		$this->runSeeder(\UserSeeder::class);

	}

	/** @test */
	public function it_seeds_a_user_with_properties() {
		$model = Model::where('name', 'Admin')->first();
		$this->assertNotNull($model);
		$this->assertEquals('admin@example.tz', $model->email);
		$this->assertTrue(Hash::check('admin22', $model->password));
		$this->assertEquals('SA', $model->usergroup->title);
	}
}
