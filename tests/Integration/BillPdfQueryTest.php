<?php

namespace Tests\Integration;

use App\Queries\BillPdfQuery;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\IntegrationTestCase;

class BillPdfQueryTest extends IntegrationTestCase {
	public function setUp() {
		parent::setUp();

		$this->runSeeder('FeeSeeder');
		$this->runSeeder('WaySeeder');
		$this->runSeeder('ConfessionSeeder');
		$this->runSeeder('RegionSeeder');
		$this->runSeeder('CountrySeeder');
		$this->runSeeder('GenderSeeder');
		$this->runSeeder('StatusSeeder');
		$this->runSeeder('NationalitySeeder');

		$this->createMany('Subscription', 3);
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
		$this->assertCount(0, BillPdfQuery::members()->get());
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
		$this->assertCount(0, BillPdfQuery::members()->get());
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

		$sub = $this->create('Subscription', ['amount' => 0]);

		$this->make('Payment', ['status_id' => 2, 'subscription_id' => $sub->id])->member()->associate($members[0])->save();
		$this->make('Payment', ['status_id' => 3, 'subscription_id' => $sub->id])->member()->associate($members[1])->save();
		$this->make('Payment', ['status_id' => 1, 'subscription_id' => $sub->id])->member()->associate($members[1])->save();

		$query = new BillPdfQuery();
		$this->assertCount(0, BillPdfQuery::members()->get());
	}

    public function validPaymentDataProvider() {
        return [
            [[1,2], [1,2,3,4,5]],
            [[1], [1,3,5]],
            [[2], [2,4]],
        ];
    }

    /**
     * @test
     * @dataProvider validPaymentDataProvider
     */
	public function it_gets_all_members_with_valid_payment_and_filters_for_way($ways, $expected) {
		$members = $this->createMany('Member', 5, [
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1],
			['way_id' => 2],
			['way_id' => 1]
		]);

		$this->make('Payment', ['status_id' => 1])->member()->associate($members[0])->save();
		$this->make('Payment', ['status_id' => 1])->member()->associate($members[1])->save();
		$this->make('Payment', ['status_id' => 1])->member()->associate($members[2])->save();
		$this->make('Payment', ['status_id' => 1])->member()->associate($members[3])->save();
		$this->make('Payment', ['status_id' => 1])->member()->associate($members[4])->save();

        $this->assertEquals(
            $expected,
            BillPdfQuery::members()->filterWays($ways)->get()->pluck('id')->sort()->values()->toArray()
        );
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

		$this->make('Payment', ['status_id' => 1])->member()->associate($members[0])->save();
		$this->make('Payment', ['status_id' => 1])->member()->associate($members[1])->save();
		$this->make('Payment', ['status_id' => 1])->member()->associate($members[2])->save();
		$this->make('Payment', ['status_id' => 1])->member()->associate($members[3])->save();
		$this->make('Payment', ['status_id' => 1])->member()->associate($members[4])->save();

		$query = BillPdfQuery::members();
		$this->assertEquals([2,1,4,3,5], $query->get()->pluck('id')->toArray());
	}
}
