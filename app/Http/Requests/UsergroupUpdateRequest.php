<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;

class UsergroupUpdateRequest extends Request
{
	public $model = \App\Usergroup::class;
	public $right = 'usergroup';

	public function authorize() {
		return auth()->guard('api')->user()->can('update', $this->route('usergroup'));
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
			'rights.*' => 'exists:rights,id'
        ];
    }

	public function messages() {
		return [
			'rights.*' => 'Dieses Recht existiert nicht.'
		];
	}
}
