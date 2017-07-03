<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use App\User;

class LoginRequest extends Request
{
	public $model = \App\User::class;

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email' => 'required|string|email',
			'password' => 'required|string'
		];
	}

	public function customRules() {
		if($this->input('email')
		  && User::where('email', $this->input('email'))->first()
		  && !User::where('email', $this->input('email'))->first()->hasRight('login')
		) {
			return [
				'email' => 'Der Login wurde für diesen Benutzer leider gesperrt.'
			];
		}
	}
}
