<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

	public function editOwnProfile($user, $model) {
		return $user->id == $model->id && $user instanceof User;
	}

	public function store($user) {
		return $user->hasRight('user');
	}

	public function update($user, $model) {
		return $user->hasRight('user');
	}

	public function destroy($user, $model) {
		return $user->hasRight('user');
	}
}
