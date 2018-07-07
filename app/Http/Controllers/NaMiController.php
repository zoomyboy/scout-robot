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
        $nami = new NaMiService();
        $nami->setUser('90166');
        $nami->setPassword('h8uHYOXinN89');
        dd($nami->newSession());
    }
}
