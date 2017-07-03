<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserPasswordRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserDestroyRequest;

class UserController extends Controller
{
	public function index() {
		return response()->json(User::with('usergroup')->get()->toArray());
	}

    public function show(User $user) {
    	return response()->json($user->load('usergroup')->toArray());
    }

	public function store(UserStoreRequest $request) {
		$request->persist();
	}

	public function update(User $user, UserUpdateRequest $request) {
		$request->persist($user);
	}

	public function destroy(User $user, UserDestroyRequest $request) {
		$request->persist($user);
	}
}
