<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nationality;

class NationalityController extends Controller
{
    public function index() {
    	return response()->json(Nationality::get()->toArray());
    }
}
