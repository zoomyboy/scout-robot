<?php

namespace Tests\Integration\Seeder;

use Tests\TestCase;
use \App\Right;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RightTest extends TestCase
{
	/** @test */
	public function it_seeds_all_confs() {
		$this->runMigration('rights_table');
		$this->runSeeder(\RightSeeder::class);

		$this->assertEquals(6, Right::count());
		$this->assertEquals(
			['login', 'user', 'usergroup', 'config', 'member.manage', 'member.overview'], 
			Right::get()->pluck('key')->toArray()
		);
	}
}
