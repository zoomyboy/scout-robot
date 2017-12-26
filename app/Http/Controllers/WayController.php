<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WayController extends Controller
{
    public function index() {
    	return response()->json(\App\Way::get()->toArray());
    }
}
