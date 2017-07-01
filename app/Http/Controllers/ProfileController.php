<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
	/**
	 * Gets the data for the currently logged in user
	 *
	 * @return JSON
	 */
    public function show() {
    	return response()->json(auth()->user()->toArray());
    }
}
