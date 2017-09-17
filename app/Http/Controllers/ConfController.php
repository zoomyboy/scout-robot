<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conf;
use App\Http\Requests\ConfUpdateRequest;

class ConfController extends Controller
{
	public function show(Conf $conf) {
		return response()->json($conf
			->load('defaultCountry')
			->load('defaultRegion')
			->toArray()
		);
	}

	public function update(Conf $conf, ConfUpdateRequest $request) {
		$request->persist($conf);
	}

	public function index() {
		return response()->json(Conf::with('defaultCountry', 'defaultRegion')->get()->toArray());
	}
}
