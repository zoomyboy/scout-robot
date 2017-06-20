<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\User;
use App\Right;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function it_checks_if_a_user_has_a_right() {
		(new \RightSeeder)->run();
		(new \UsergroupSeeder)->run();
		(new \UserSeeder)->run();

		$user = User::first();
		$this->assertTrue($user->hasRight('login'));

		$user->usergroup->rights()->detach(Right::where('key', 'login')->first());

		$this->assertFalse($user->hasRight('login'));
	}
}

