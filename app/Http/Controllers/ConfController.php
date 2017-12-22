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
			->load('files')
			->load('deadlineunit')
			->toArray()
		);
	}

	public function update(Conf $conf, ConfUpdateRequest $request) {
		$model = $request->persist($conf);

		return response()->json($model->load('files')->toArray());
	}

	public function index() {
		return response()->json(Conf::with('defaultCountry', 'defaultRegion', 'files', 'deadlineunit')->get()->toArray());
	}
}
