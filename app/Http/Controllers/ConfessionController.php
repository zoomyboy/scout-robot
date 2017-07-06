<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Confession;

class ConfessionController extends Controller
{
	public function index() {
		return response()->json(Confession::get()->toArray());
	}
}
