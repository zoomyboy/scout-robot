<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthuserController extends Controller
{
	public function show() {
		if (!auth()->user()) {
			return 'a';
		}

    	return response()->json(auth()->user()->load('usergroup.rights')->toArray());
    }
}
