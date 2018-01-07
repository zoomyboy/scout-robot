<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Mass\EmailBillRequest;

class MassController extends Controller
{
    public function email(EmailBillRequest $request) {
    	$request->persist();
    }
}
