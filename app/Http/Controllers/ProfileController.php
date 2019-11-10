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
    public function update(User $user, ProfileUpdateRequest $request) {
        $request->persist($user);
    }

    /* @deprecated */
    public function updatePassword(User $user, ProfilePasswordUpdateRequest $request) {
        $request->persist($user);
    }

    /* @deprecated */
    public function freeinfo() {
        return response()->json([
            'app' => array_only(config('app'), ['name'])
        ]);
    }
}
