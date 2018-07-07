<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\NaMi\NaMiMember;
use App\Http\Requests\NaMiGetRequest;
use App\Services\NaMi\NaMiService;

class NaMiController extends Controller
{
    public function getmembers(NaMiGetRequest $request) {
		$request->persist();
    }

    public function login() {
        $nami = app(NaMiService::class);
        dd($nami->newSession());
    }
}
