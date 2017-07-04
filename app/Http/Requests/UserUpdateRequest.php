<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends Request
{
	public $model = \App\User::class;
	public $right = 'user';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$pwRules = ['confirmed'];
		if ($this->input('password') || $this->input('password_confirmation')) {
			$pwRules[] = 'min:6';
		}

		return [
			'name' => 'required|min:3',
			'email' => ['required', 'email', Rule::unique('users')->ignore($this->route()->parameter('user')->id)],
			'usergroup' => 'required',
			'password' => $pwRules
		];
    }
}

