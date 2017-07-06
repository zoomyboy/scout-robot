<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Region;

class RegionController extends Controller
{
	public function index() {
		return response()->json(Region::get()->toArray());
	}

	public function default() {
		if (is_null(Region::default())) {
			return;
		}
		return response()->json(Region::default()->getAttributes());
	}
}
