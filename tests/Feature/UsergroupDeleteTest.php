<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use App\Usergroup;
use App\Right;
use Tests\FeatureTestCase;

class UsergroupDeleteTest extends FeatureTestCase {

	public function setUp() {
		parent::setUp();

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
		$this->withExceptionHandling();
		$this->authAsApi();

		Usergroup::find(2)->users()->get()->each(function($m) {
			$m->usergroup()->dissociate(Usergroup::find(2));
			$m->save();
		});

		$this->deleteApi('usergroup/2')->assertSuccess();

		$usergroup = Usergroup::whereTitle('NEW')->first();
		$this->assertNull($usergroup);
	}

	/** @test */
	public function it_can_only_delete_usergroups_if_it_has_permission() {
		$this->withExceptionHandling();
		$this->authAsApi();

		auth()->user()->usergroup->rights()->detach(Right::where('key', 'usergroup')->first());
		$this->deleteApi('usergroup/2')->assertForbidden();
	}

	/** @test */
	public function it_shouldnt_be_a_guest() {
		$this->withExceptionHandling();

		$this->deleteApi('usergroup/2')->assertUnauthorized();
	}

	/** @test */
	public function it_can_only_delete_a_usergroup_that_exists() {
		$this->withExceptionHandling();
		$this->authAsApi();

		$this->deleteApi('usergroup/200')->assertNotFound();
	}

	/** @test */
	public function it_can_only_delete_a_usergroup_that_has_no_members() {
		$this->withExceptionHandling();
		$this->authAsApi();

		$usergroup = $this->create('usergroup');
		$newUser = $this->create('user', ['usergroup_id' => $usergroup->id]);


		$this->deleteApi('usergroup/'.$usergroup->id)->assertValidationFailedWith('id');
	}
}
