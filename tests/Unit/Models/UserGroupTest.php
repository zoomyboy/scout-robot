<?php

namespace Tests\Unit\Seeder;

use Tests\TestCase;
use \App\Right as Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserGroupTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function it_seeds_a_right() {
		$seeder = new \RightSeeder();
		$seeder->run();
		$model = Model::where('key', 'login')->first();
		$this->assertNotNull($model);
		$this->assertEquals('Einloggen', $model->title);
		$this->assertEquals('In Scout Robot mit seinen eigenen Benutzerdaten einloggen', $model->help);
	}
}


