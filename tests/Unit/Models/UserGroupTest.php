<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Usergroup;
use App\Right;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserGroupTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function it_checks_if_a_group_has_a_right() {
		(new \RightSeeder)->run();
		(new \UsergroupSeeder)->run();

		$usergroup = Usergroup::first();
		$this->assertTrue($usergroup->hasRight('login'));

		$usergroup->rights()->detach(Right::where('key', 'login')->first());

		$this->assertFalse($usergroup->hasRight('login'));
	}
}


