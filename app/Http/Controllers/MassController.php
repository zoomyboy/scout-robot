<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Mass\EmailBillRequest;
use App\Http\Requests\Mass\EmailRememberRequest;

class MassController extends Controller
{
    public function bill(EmailBillRequest $request) {
    	$request->persist();
    }

    public function remember(EmailRememberRequest $request) {
    	$request->persist();
    }
}
