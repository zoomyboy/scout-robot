<?php

namespace Tests\Integration\Seeder;

use Tests\TestCase;
use \App\Usergroup;
use \App\Right;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;

class UsergroupTtest extends TestCase
{
	public function setUp() {
		parent::setUp();
		Config::set('seed.default_usergroup', 'SA');
		$this->runMigration('rights_table');
		$this->runSeeder(\RightSeeder::class);
		$this->runMigration('usergroups_table');
		$this->runMigration('right_usergroup_table');
		$this->runSeeder(\UsergroupSeeder::class);
	}

	/** @test */
	public function it_seeds_a_default_user_group_and_assigns_all_rights() {
		$model = Usergroup::where('title', 'SA')->first();
		$this->assertNotNull($model);

		$this->assertEquals(Right::get()->count(), $model->rights->count());
	}
}
