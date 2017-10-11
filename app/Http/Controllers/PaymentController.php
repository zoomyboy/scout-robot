<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Member;

class PaymentController extends Controller
{
    public function index(Member $member) {
    	return response()->json($member->load('payments.status')->payments->toArray());
    }
}
