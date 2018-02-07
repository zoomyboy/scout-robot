<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use App\Payment;
use App\Http\Requests\Payment\StoreRequest;
use App\Http\Requests\Payment\UpdateRequest;

class MemberPaymentsController extends Controller
{
    public function index(Member $member) {
        return response()->json($member->payments()->with(['subscription.fee', 'status'])->get()->toArray());
    }

    public function store(Member $member, StoreRequest $request) {
        $request->persist();
    }

    public function update(Member $member, Payment $payment, UpdateRequest $request) {
        $request->persist($payment);

        $payment = $payment->fresh(['subscription', 'status']);

        return response()->json($payment->toArray());
    }
}
