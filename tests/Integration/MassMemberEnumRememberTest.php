<?php

namespace Tests\Integration;

use Tests\UnitTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Notifications\EmailRememberNotification;

class MassMemberEnumRememberTest extends UnitTestCase {
	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();

		$this->runSeeder('CountrySeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('NationalitySeeder');
	}

	public function dataProvider() {
		return [
			[
				[],
				''
			],
			[
				[
					['firstname' => 'John', 'lastname' => 'Doe']
				],
				'John Doe'
			],
			[
				[
					['firstname' => 'John', 'lastname' => 'Doe'],
					['firstname' => 'Jane', 'lastname' => 'Doe'],
				],
				'John Doe und Jane Doe'
			],
			[
				[
					['firstname' => 'John', 'lastname' => 'Doe'],
					['firstname' => 'Jane', 'lastname' => 'Doe'],
					['firstname' => 'Jare', 'lastname' => 'Doe'],
				],
				'John Doe, Jane Doe und Jare Doe'
			],
			[
				[
					['firstname' => 'John', 'lastname' => 'Doe'],
					['firstname' => 'Jane', 'lastname' => 'Doe'],
					['firstname' => 'Jare', 'lastname' => 'Doe'],
					['firstname' => 'Jare2', 'lastname' => 'Doe2'],
				],
				'John Doe, Jane Doe, Jare Doe und Jare2 Doe2'
			]
		];
	}

	/**
	 * @test
	 * @dataProvider dataProvider
	 */
	public function it_returns_an_empty_string_when_no_members_given($members, $string) {
		$members = array_map(function($m) {
			return $this->create('Member', $m);
		}, $members);

		$n = new EmailRememberNotification($this->create('Member'), true, '', collect($members));
		$this->assertEquals($string, $n->generateMemberString());
	}
}
