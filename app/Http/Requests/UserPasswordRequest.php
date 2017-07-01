<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;

class UserPasswordRequest extends Request
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
            'password' => 'required|confirmed'
        ];
    }
}
