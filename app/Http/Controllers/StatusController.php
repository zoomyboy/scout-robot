<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;

class StatusController extends Controller
{
    public function index() {
    	return response()->json(Status::get());
    }
}
