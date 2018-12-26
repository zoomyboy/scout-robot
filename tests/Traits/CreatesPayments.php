<?php

namespace Tests\Traits;

use App\Status;
use App\Subscription;

trait CreatesPayments {
    public function createPayment($member, $status, $subscription, $nr) {
        $member->createPayment([
            'status_id' => Status::title($status)->first()->id,
            'subscription_id' => Subscription::title($subscription)->first()->id,
            'nr' => $nr
        ]);
    }
}
