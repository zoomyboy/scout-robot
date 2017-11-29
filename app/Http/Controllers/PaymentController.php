<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Member;
use App\Http\Requests\Payment\PaymentStoreRequest;
use App\Http\Requests\Payment\PaymentUpdateRequest;
use App\Payment;

class PaymentController extends Controller
{
    public function index(Member $member) {
    	return response()->json($member->load('payments.status')->payments->toArray());
    }

	public function store(Member $member, PaymentStoreRequest $request) {
		$request->persist();
	}

	public function update(Member $member, Payment $payment, PaymentUpdateRequest $request) {
		$request->persist($payment);
	}

	public function destroy(Payment $payment) {
		$payment->delete();
	}
}
