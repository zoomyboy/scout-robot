<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use App\User;

class UserStoreRequest extends Request
{
	public $model = \App\User::class;
		
	public function authorize() {
		return auth()->guard('api')->user()->can('store', User::class);
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$required = [
			'name' => 'required|min:3',
			'email' => ['required', 'unique:users,email'],
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
			$fill['password'] = str_random(30);	//Set random password that user can change afterwards
		} else {
			$fill['password'] = bcrypt($fill['password']);
		}

		return $fill;
	}

	public function messages() {
		return ['email.unique' => 'Diese E-Mail-Adresse gibt es bereits'];
	}

	/**
	 * Send Email to new User with Password request
	 *
	 * @param User $model The inserted User model
	 */
	public function afterPersist($user) {
		if ($this->input('sendemail') && !$this->input('nopw')) {
			//Passwort wurde von Admin gesetzt - sende User sein neues Passwort zu
			$user->sendPasswordAfterCreationNotification($this->input('password'));
			return;
		}

		if ($this->input('sendemail') && $this->input('nopw')) {
			$tokens = app()->make('auth.password')->broker('firstusers')->getRepository();
			$user->sendTokenAfterCreationNotification($tokens->create($user));
		}
	}
}
