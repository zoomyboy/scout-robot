<?php

namespace App\Http\Controllers;

use App\Facades\NaMi\NaMiMember;
use App\Http\Requests\NamiGetRequest;
use App\Services\NaMi\NaMiService;
use Illuminate\Http\Request;

class NaMiController extends Controller
{
    public function getmembers(NamiGetRequest $request) {
		$request->persist();
    }
}
