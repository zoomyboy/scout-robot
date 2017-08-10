<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use App\Http\Requests\Member\MemberStoreRequest;

class MemberController extends Controller
{

	public function index() {
		$this->authorize('index', Member::class);
	}

    public function store(MemberStoreRequest $request) {
    	$request->persist();
    }
}
