<?php

namespace Tests\Unit;

use Tests\UnitTestCase;
use App\Queries\BillPdfQuery;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BillPdfQueryTest extends UnitTestCase {

	use DatabaseMigrations;

	public function setUp() {
		parent::setUp();
		
		$this->runSeeder('WaySeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('StatusSeeder');
		$this->runSeeder('NationalitySeeder');
	}

	/** @test */
	public function it_gets_no_values_if_members_have_no_payments() {
		$members = $this->createMany('Member', 5, [
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1]
		]);

		$query = new BillPdfQuery();
		$this->assertEquals(0, $query->handle()->count());
	}

	/** @test */
	public function it_gets_no_values_if_members_havent_any_not_paid_payments() {
		$members = $this->createMany('Member', 5, [
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1]
		]);

		$this->make('Payment', ['status_id' => 2])->member()->associate($members[0])->save();
		$this->make('Payment', ['status_id' => 3])->member()->associate($members[1])->save();
		$this->make('Payment', ['status_id' => 2])->member()->associate($members[1])->save();

		$query = new BillPdfQuery();
		$this->assertEquals(0, $query->handle()->count());
	}

	/** @test */
	public function it_gets_no_values_if_members_have_zero_amount() {
		$members = $this->createMany('Member', 5, [
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1]
		]);

		$this->make('Payment', ['status_id' => 2, 'amount' => 0])->member()->associate($members[0])->save();
		$this->make('Payment', ['status_id' => 3, 'amount' => 0])->member()->associate($members[1])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 0])->member()->associate($members[1])->save();

		$query = new BillPdfQuery();
		$this->assertEquals(0, $query->handle()->count());
	}

	/** @test */
	public function it_gets_all_members_with_valid_payment_and_filters_for_way() {
		$members = $this->createMany('Member', 5, [
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1]
		]);

		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[0])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[1])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[2])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[3])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[4])->save();

		$query = new BillPdfQuery();
		$this->assertEquals([1,2,3,4,5], $query->handle([1,2])->get()->pluck('id')->sort()->values()->toArray());
		$this->assertEquals([1,3,5], $query->handle([1])->get()->pluck('id')->sort()->values()->toArray());
		$this->assertEquals([2,4], $query->handle([2])->get()->pluck('id')->sort()->values()->toArray());
	}

	/** @test */
	public function it_orders_members_by_firstname_and_lastname() {
		$members = $this->createMany('Member', 5, [
			['way_id' => 1, 'lastname' => 'G'],
			['way_id' => 2, 'lastname' => 'B'],
			['way_id' => 1, 'lastname' => 'P', 'firstname' => 'Z'],
			['way_id' => 2, 'lastname' => 'P', 'firstname' => 'A'],
			['way_id' => 1, 'lastname' => 'U']
		]);

		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[0])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[1])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[2])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[3])->save();
		$this->make('Payment', ['status_id' => 1, 'amount' => 10])->member()->associate($members[4])->save();

		$query = new BillPdfQuery();
		$this->assertEquals([2,1,4,3,5], $query->handle([1,2])->get()->pluck('id')->toArray());
	}
}
