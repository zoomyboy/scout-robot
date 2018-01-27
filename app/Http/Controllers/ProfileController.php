<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePasswordUpdateRequest;
use App\User;
use App\Conf;

class ProfileController extends Controller
{
	/**
	 * Gets the data for the currently logged in user
	 *
	 * @return JSON
	 */
    public function index() {
    	return response()->json(auth()->guard('api')->user()->load('usergroup.rights')->toArray());
    }

	public function update(User $user, ProfileUpdateRequest $request) {
		$request->persist($user);
	}

	public function updatePassword(User $user, ProfilePasswordUpdateRequest $request) {
		$request->persist($user);
	}

	public function infoForCurrentUser() {
		return response()->json([
			'conf' => Conf::first()->toArray(),
            'countries' => \App\Country::get()->toArray(),
            'regions' => \App\Region::get()->toArray(),
            'fees' => \App\Fee::get()->toArray(),
            'nationalities' => \App\Nationality::get()->toArray(),
            'user' => auth()->guard('api')->user()->load(['usergroup.rights'])->toArray(),
            'app' => array_only(config('app'), ['name'])
		]);
	}

	public function freeinfo() {
		return response()->json([
            'app' => array_only(config('app'), ['name'])
		]);
	}
}
