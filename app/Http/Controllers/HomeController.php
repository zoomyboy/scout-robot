<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


		foreach(\App\Facades\NaMi\NaMiCountry::all() as $c) {
			echo "Country::create(['title' => '".$c->descriptor."', 'nami_id' => ".$c->id.", 'nami_title' => '".$c->descriptor."']);<br>";
		}

		exit;

		if (auth()->guard('web')->check()) {
        	return view('home');
		} else {
        	return view('free');
		}
    }
}
