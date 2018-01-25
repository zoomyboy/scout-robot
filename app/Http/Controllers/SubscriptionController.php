<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\Subscription\SubscriptionStoreRequest;
use App\Http\Requests\Subscription\SubscriptionUpdateRequest;
use App\Subscription;

class SubscriptionController extends Controller
{
	public function show(Subscription $subscription) {
		return response()->json($subscription->load('fee')->toArray());
	}

    public function index() {
    	return response()->json(\App\Subscription::with('fee')->get());
    }

	public function store(SubscriptionStoreRequest $request) {
		$request->persist();
	}

	public function update(Subscription $sub, SubscriptionUpdateRequest $request) {
		$request->persist($sub);
	}
}
