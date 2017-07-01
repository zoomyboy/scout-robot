<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserPasswordRequest;

class UserController extends Controller
{
	public function index() {
		return response()->json(User::get()->toArray());
	}

    public function show(User $user) {
    	return response()->json($user->toArray());
    }

	public function update(User $user, UserUpdateRequest $request) {
		$request->persist($user);
	}

	public function password(User $user, UserPasswordRequest $request) {
		$request->persist($user);
	}
}
