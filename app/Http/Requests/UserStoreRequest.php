<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;

class UserStoreRequest extends Request
{
	public $model = \App\User::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$required = [
			'name' => 'required|min:3',
			'email' => 'required|email',
			'usergroup' => 'required'
		];

		if (!$this->input('nopw')) {
			$required['password'] = 'required|confirmed';
		}

		return $required;
    }

	/**
	 * Setze ein zufälliges Passwort was keiner kennt
	 * weil der Benutzer sein Passwort eh selbst ändert
	 *
	 * @param array $fill vorherige Fillables
	 *
	 * @return array Modifizierte fillables mit zufälligem PW
	 */
	public function modifyFillables($fill) {
		if ($this->input('nopw')) {
			$fill['password'] = str_random(30);
		}

		return $fill;
	}

	/**
	 * Send Email to new User with Password request
	 *
	 * @param User $model The inserted User model
	 */
	public function afterPersist($user) {
		$tokens = app()->make('auth.password')->broker('firstusers')->getRepository();

		$user->sendPasswordAfterCreationNotification($tokens->create($user));
	}
}
