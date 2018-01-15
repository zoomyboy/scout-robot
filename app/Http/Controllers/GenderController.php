<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gender;

class GenderController extends Controller
{
    public function index() {
    	return response()->json(Gender::where('is_null', false)->get()->toArray());
    }
}
