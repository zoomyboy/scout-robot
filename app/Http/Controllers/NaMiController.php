<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\NaMi\NaMiMember;
use App\Http\Requests\NaMiGetRequest;

class NaMiController extends Controller
{
    public function getmembers(NaMiGetRequest $request) {
		$request->persist();
    }
}
