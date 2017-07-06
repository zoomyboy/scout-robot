<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Country;

class CountryController extends Controller
{
	public function index() {
		return response()->json(Country::get()->toArray());
	}

	public function default() {
		if (is_null(Country::default())) {
			return;
		}
		return response()->json(Country::default()->getAttributes());
	}
}
