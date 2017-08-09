<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;

class ProfilePasswordUpdateRequest extends Request
{
	public $model = \App\User::class;

	public function authorize() {
		return auth()->guard('api')->user()->can('editOwnProfile', $this->route('profile'));
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'password' => 'required|confirmed|min:6'
        ];
    }

	/**
	 * Prevent the User from changing e.g. its usergroup by setting the id in the POST data
	 *
	 * @param array $fill The old Fillables
	 * @return $fill The new fillables - only password
	 */
	public function modifyFillables($fill) {
		return ['password' => bcrypt($fill['password'])];
	}
}
