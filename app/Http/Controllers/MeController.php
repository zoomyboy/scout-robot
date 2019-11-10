<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;

class MeController extends Controller
{

    public function index() {
        return new ProfileResource(auth()->guard('api')->user()->load('usergroup.rights'));
    }

}
