<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Right;

class RightController extends Controller
{
    public function index() {
		return response()->json(Right::get()->toArray());
    }
}
