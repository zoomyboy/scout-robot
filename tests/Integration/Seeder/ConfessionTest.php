<?php

namespace Tests\Integration\Seeder;

use Tests\TestCase;
use \App\Confession;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConfessionTest extends TestCase
{
	/** @test */
	public function it_seeds_all_confs() {
		$this->runMigration('confessions_table');
		$this->runSeeder(\ConfessionSeeder::class);

		$this->assertEquals(10, Confession::count());
		$this->assertEquals(
			['Römisch-Katholisch', 'Evangelisch / Protestantisch', 'Orthodox', 'Freikirchlich', 'Andere christliche', 'Jüdisch', 'Muslimisch', 'Sonstige', 'Neuapostolisch', 'Ohne Konfession'], 
			Confession::get()->pluck('title')->toArray()
		);
	}
}
