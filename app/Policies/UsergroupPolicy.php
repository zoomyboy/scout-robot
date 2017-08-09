<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsergroupPolicy
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
		return $user->hasRight('usergroup');
	}

	public function update($user, $model) {
		return $user->hasRight('usergroup');
	}

	public function destroy($user, $model) {
		return $user->hasRight('usergroup');
	}
}
