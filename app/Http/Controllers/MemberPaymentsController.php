<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use App\Payment;
use App\Http\Requests\Payment\StoreRequest;
use App\Http\Requests\Payment\UpdateRequest;
use App\Status;

class MemberPaymentsController extends Controller
{
    public function index(Member $member) {
        return response()->json($member->payments()->with(['subscription.fee', 'status'])->get()->toArray());
    }

    public function store(Member $member, StoreRequest $request) {
        return response()->json($request->persist());
    }

    public function update(Member $member, Payment $payment, UpdateRequest $request) {
        return response()->json($request->persist($payment));
    }

    public function destroy(Member $member, Payment $payment) {
        $payment->delete();

        return response()->json([
            'strikes' => $member->strikes
        ]);
    }
}
