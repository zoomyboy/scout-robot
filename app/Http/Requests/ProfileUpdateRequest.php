<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends Request
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
			'name' => 'required',
			'email' => ['required', 'email', Rule::unique('users')->ignore($this->route()->parameter('profile')->id)]
        ];
    }

	/**
	 * Prevent the User from changing e.g. its usergroup by setting the id in the POST data
	 *
	 * @param array $fill The old Fillables
	 * @return $fill The new fillables - only name and email
	 */
	public function modifyFillables($fill) {
		return array_only($fill, ['name', 'email']);
	}
}
