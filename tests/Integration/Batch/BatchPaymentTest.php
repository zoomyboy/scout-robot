<?php

namespace Tests\Integration\Batch;

use Tests\IntegrationTestCase;
use App\Member;

class BatchPaymentTest extends IntegrationTestCase {
    public function setUp() {
        parent::setUp();

        $this->runSeeder('DatabaseSeeder');
    }

    /** @test */
    public function it_creates_missing_payments_for_all_members_for_the_given_year() {
        $this->authAsApi();

        $members = factory(Member::class, 5)->create(['subscription_id' => 1]);

        foreach($members as $member) {
            $this->assertEmpty($member->payments);
        }

        $this->postApi('payments/batch', ['nr' => 2018])
            ->assertSuccess();

        foreach($members as $member) {
            $member = $member->fresh('payments');
            $this->assertNotEmpty($member->payments);

            $this->assertEquals(2018, $member->payments->first()->nr);
            $this->assertEquals(1, $member->payments->first()->status->id);
        }
    }
}
