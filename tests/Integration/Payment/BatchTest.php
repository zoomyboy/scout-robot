<?php

namespace Tests\Integration\Batch;

use Tests\IntegrationTestCase;
use App\Member;
use App\Subscription;
use App\Fee;

class BatchTest extends IntegrationTestCase {

    public $subscription;

    public function setUp() {
        parent::setUp();

        $this->seedForMember();
        $this->runSeeder('StatusSeeder');
        $fee = factory(Fee::class)->create();
        $this->subscription = factory(Subscription::class)->create([
            'fee_id' => $fee->id,
            'amount' => 3500
        ]);
    }

    /** @test */
    public function it_creates_missing_payments_for_all_members_for_the_given_year() {
        $this->authAsApi();

        $member = factory(Member::class)->create(['subscription_id' => $this->subscription->id]);

        $this->assertEmpty($member->payments);

        $this->postApi('paymentbatch', ['nr' => 2018])
            ->assertSuccess();

        $this->assertDatabaseHas('payments', [
            'member_id' => $member->id,
            'status_id' => 1,
            'subscription_id' => $this->subscription->id,
            'nr' => 2018
        ]);
    }

    /** @test */
    public function it_doesnt_create_a_new_payment_if_this_payment_already_exists() {
        $this->authAsApi();

        $member = factory(Member::class)->create(['subscription_id' => $this->subscription->id]);
        $payment = $member->payments()->create([
            'subscription_id' => $this->subscription->id,
            'status_id' => 1,
            'nr' => 2018
        ]);

        $this->assertCount(1, $member->payments()->get());

        $this->postApi('paymentbatch', ['nr' => 2018])
            ->assertSuccess();

        $this->assertCount(1, $member->payments()->get());
    }
}
