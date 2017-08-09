<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use Illuminate\Validation\Rule;
use App\User;

class UserUpdateRequest extends Request
{
	public $model = \App\User::class;

	public function authorize() {
		return auth()->guard('api')->user()->can('update', $this->route('user'));
	}

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
			'email' => ['required', 'email', Rule::unique('users')->ignore($this->route('user')->id)],
			'usergroup' => 'required',
			'password' => $pwRules
		];
    }

	public function modifyFillables($fill) {
		if (!$this->input('password')) {
			return array_except($fill, ['password']);
		}

		$fill['password'] = bcrypt($fill['password']);
		
		return $fill;
	}

	public function messages() {
		return [
			'email.unique' => 'Diese E-Mail-Adresse gehört bereits einem anderen Benutzer.'
		];
	}

}

