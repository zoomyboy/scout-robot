<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;

class UsergroupUpdateRequest extends Request
{
	public $model = \App\Usergroup::class;
	public $right = 'usergroup';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required'
        ];
    }
}
