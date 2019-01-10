<?php

namespace App\Feature\Member;

use App\Status;
use App\Subscription;
use Tests\FeatureTestCase;

class PaymentsTest extends FeatureTestCase {
    public $config;

    public function setUp() {
        parent::setUp();

        $this->createConfigs();
        $this->prepareMembers();

        $this->authAsApi();
    }

    /** @test */
    public function it_stores_a_payment_for_an_existing_member() {
        $member = $this->create('Member');
        $member->createPayment([
            'nr' => '2017',
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Voll')->first()->id
        ]);

        $this->postApi("member/{$member->id}/payments", [
            'nr' => '2016',
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Voll')->first()->id
        ])->assertJson([
            'strikes' => 10000,
            'payment' => ['nr' => '2016']
        ]);

        $this->assertDatabaseHas('payments', [
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Voll')->first()->id,
            'nr' => '2016',
            'member_id' => $member->id
        ]);
        $this->assertDatabaseHas('payments', [
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Voll')->first()->id,
            'nr' => '2017',
            'member_id' => $member->id
        ]);
    }

    /** @test */
    public function it_updates_an_existing_payment() {
        $member = $this->create('Member');
        $payment = $member->createPayment([
            'nr' => '2017',
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Voll')->first()->id
        ]);

        $this->patchApi("member/{$member->id}/payments/{$payment->id}", [
            'nr' => '2015',
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Familie')->first()->id
        ])->assertJson([
            'strikes' => 4000,
            'payment' => ['id' => $payment->id]
        ]);

        $this->assertDatabaseHas('payments', [
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Familie')->first()->id,
            'nr' => '2015',
            'member_id' => $member->id
        ]);
    }

    /** @test */
    public function it_deletes_an_existing_payment() {
        $member = $this->create('Member');
        $payment = $member->createPayment([
            'nr' => '2017',
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Voll')->first()->id
        ]);
        $familyPayment = $member->createPayment([
            'nr' => '2018',
            'status_id' => Status::title('Nicht bezahlt')->first()->id,
            'subscription_id' => Subscription::title('Familie')->first()->id
        ]);

        $this->deleteApi("member/{$member->id}/payments/{$payment->id}")->assertJson([
            'strikes' => 4000
        ]);

        $this->assertDatabaseMissing('payments', [
            'nr' => '2017',
            'member_id' => $member->id
        ]);
    }
}

