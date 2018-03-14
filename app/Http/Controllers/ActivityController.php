<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        return response()->json(Activity::with('groups')->get()->toArray());
    }
}
