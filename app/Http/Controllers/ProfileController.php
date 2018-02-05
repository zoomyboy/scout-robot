<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePasswordUpdateRequest;
use App\User;
use App\Conf;
use App\Unit;
use App\Gender;

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
            'activities' => \App\Activity::with('groups')->get()->toArray(),
            'confessions' => \App\Confession::get()->toArray(),
            'fees' => \App\Fee::get()->toArray(),
            'ways' => \App\Way::get()->toArray(),
            'nationalities' => \App\Nationality::get()->toArray(),
            'subscriptions' => \App\Subscription::with('fee')->get()->toArray(),
            'user' => auth()->guard('api')->user()->load(['usergroup.rights'])->toArray(),
    		'timeunits' => Unit::ofType('date')->get()->toArray(),
    		'genders' => Gender::where('is_null', false)->get()->toArray(),
            'app' => array_only(config('app'), ['name'])
		]);
	}

	public function freeinfo() {
		return response()->json([
            'app' => array_only(config('app'), ['name'])
		]);
	}
}
