<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;

class UnitController extends Controller
{
    public function index($type) {
    	return response()->json(Unit::ofType($type)->get()->toArray());
    }
}
