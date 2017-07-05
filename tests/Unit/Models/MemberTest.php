<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Member;
use App\Country;
use App\Gender;
use App\Region;
use App\Confession;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemberTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function it_creates_a_model() {
		(new \CountrySeeder)->run();
		(new \RegionSeeder)->run();
		(new \ConfessionSeeder)->run();
		(new \GenderSeeder)->run();

		$member = new Member([
			'firstname' => 'Max',
			'lastname' => 'Mustermann',
			'nickname' => 'Maxi',
			'other_country' => 'sky',
			'birthday' => '2085-05-05',
			'joined_at' => '1991-01-05',
			'keepdata' => true,
			'sendnewspaper' => true,
			'address' => 'Musterstr 6',
			'further_address' => 'Hinterhof',
			'zip' => '55667',
			'city' => 'Berlin',
			'phone' => '+49 121 454545',
			'mobile' => '+49 454 45533',
			'business_phone' => '+49 787 12134',
			'fax' => '+49 121 78255',
			'email' => 'max@muster.de',
			'email_parents' => 'max@muster3.de'
		]);

		$member->country()->associate(Country::where('title', 'Deutschland')->first());
		$member->gender()->associate(Gender::where('title', 'Männlich')->first());
		$member->region()->associate(Region::where('title', 'Nordrhein-Westfalen')->first());
		$member->confession()->associate(Confession::where('title', 'Römisch-Katholisch')->first());

		$member->save();


		$this->assertEquals('Max', $member->firstname);
		$this->assertEquals('Mustermann', $member->lastname);
		$this->assertEquals('Maxi', $member->nickname);
		$this->assertEquals('sky', $member->other_country);
		$this->assertEquals('2085-05-05', $member->birthday);
		$this->assertEquals('1991-01-05', $member->joined_at);
		$this->assertEquals(true, $member->keepdata);
		$this->assertEquals(true, $member->sendnewspaper);
		$this->assertEquals('Musterstr 6', $member->address);
		$this->assertEquals('Hinterhof', $member->further_address);
		$this->assertEquals('55667', $member->zip);
		$this->assertEquals('Berlin', $member->city);
		$this->assertEquals('+49 121 454545', $member->phone);
		$this->assertEquals('+49 454 45533', $member->mobile);
		$this->assertEquals('+49 787 12134', $member->business_phone);
		$this->assertEquals('+49 121 78255', $member->fax);
		$this->assertEquals('max@muster.de', $member->email);
		$this->assertEquals('max@muster3.de', $member->email_parents);

		$this->assertEquals('Deutschland', $member->country->title);
		$this->assertEquals('Männlich', $member->gender->title);
		$this->assertEquals('Nordrhein-Westfalen', $member->region->title);
		$this->assertEquals('Römisch-Katholisch', $member->confession->title);
	}
}


