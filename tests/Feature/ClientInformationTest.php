<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use App\Usergroup;
use App\Right;

class ClientInformationTest extends TestCase {

	public function setUp() {
		parent::setUp();

		Config::set('seed.default_usergroup', 'NewUserGroup');
		Config::set('seed.default_username', 'Admin');
		Config::set('seed.default_userpw', 'admin22');
		Config::set('seed.default_usermail', 'admin@example.tz');
		Config::set('seed.default_country', 'BF');

		$this->runMigration('users_table');
		$this->runMigration('usergroups_table');
		$this->runMigration('rights_table');
		$this->runMigration('right_usergroup_table');
		$this->runMigration('confs_table');
		$this->runMigration('countries_table');
		$this->runMigration('regions_table');

		$this->runSeeder(\RightSeeder::class);
		$this->create('usergroup', ['title' => 'NewUserGroup'])->rights()->sync([2,3]);
		$this->runSeeder(\UserSeeder::class);
		$this->runSeeder(\CountrySeeder::class);
		$this->runSeeder(\RegionSeeder::class);
		$this->runSeeder(\ConfSeeder::class);
	}

	/** @test */
	public function it_can_get_client_info() {
		$user = parent::auth('api');

		$this->getApi('info')
			->assertSuccess()
			->assertJson(['conf' => [
				'default_country' => [
					'id' => 40,
					'code' => 'BF',
					'title' => 'Burkina Faso'
				],
				'default_keepdata' => false,
				'default_region' => null,
				'default_sendnewspaper' => false
			], 'user' => [
				'name' => 'Admin',
				'email' => 'admin@example.tz',
				'id' => 1,
				'usergroup' => [
					'id' => 1,
					'title' => 'NewUserGroup',
					'rights' => [
						['id' => 2, 'key' => 'user', 'title' => 'Benutzer bearbeiten'],
						['id' => 3, 'key' => 'usergroup', 'title' => 'Benutzergruppen bearbeiten']
					]
				]
			]]);
	}

	/** @test */
	public function it_shouldnt_be_a_guest() {
		$this->getApi('info')->assertUnauthorized();
	}
}
