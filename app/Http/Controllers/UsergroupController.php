<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usergroup;
use App\Http\Requests\UsergroupStoreRequest;
use App\Http\Requests\UsergroupUpdateRequest;
use App\Http\Requests\UsergroupDeleteRequest;

class UsergroupController extends Controller
{
    public function index() {
		return response()->json(Usergroup::get()->toArray());
    }

	public function store(UsergroupStoreRequest $request) {
		$request->persist();
	}

	public function show(Usergroup $usergroup) {
		return response()->json($usergroup->load('rights')->toArray());
	}

	public function update(Usergroup $usergroup, UsergroupUpdateRequest $request) {
		$request->persist($usergroup);
	}

	public function destroy(UsergroupDeleteRequest $request, Usergroup $usergroup) {
		$request->persist($usergroup);
	}
}
