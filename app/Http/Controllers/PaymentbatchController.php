<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentBatch\StoreRequest;

class PaymentbatchController extends Controller
{
    public function store(StoreRequest $request) {
        $request->persist();
    }
}
