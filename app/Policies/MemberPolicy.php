<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

	public function store($user) {
		return $user->hasRight('member.manage');
	}

	public function index($user) {
		return $user->hasRight('member.overview');
	}

	public function update($user, $model) {
		return $user->hasRight('member.manage');
	}
}
