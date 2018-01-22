<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use App\Http\Requests\Member\MemberStoreRequest;
use App\Http\Requests\Member\MemberUpdateRequest;
use App\Http\Requests\Member\MemberDeleteRequest;

class MemberController extends Controller
{

	public function index() {
		$this->authorize('index', Member::class);
		return response()->json(Member::orderBy('lastname, firstname')->active()->get()->toArray());
	}

    public function store(MemberStoreRequest $request) {
    	$request->persist();
    }

	public function update(Member $member, MemberUpdateRequest $request) {
		$request->persist($member);
	}

	public function show(Member $member) {
		$this->authorize('index', Member::class);
		return response()->json($member->load(['gender', 'region', 'country', 'confession', 'way', 'nationality'])->toArray());
	}

	public function destroy(Member $member, MemberDeleteRequest $request) {
		$request->persist($member);
	}

	public function table() {
		
		return response()->json(Member::orderByRaw('lastname, firstname')->select([
			'active', 'address', 'zip', 'city', 'firstname', 'id', 'joined_at', 'lastname'
		])->get()->each(function($member) {
			$member->append('strikes');
		})->toArray());

	}

	public function tableOne(Member $member) {
		return response()->json(array_only($member->append('strikes')->toArray(), ['active', 'address', 'zip', 'city', 'firstname', 'id', 'joined_at', 'lastname', 'strikes']));
	}
}
