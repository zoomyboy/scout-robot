<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use App\Usergroup;

class UsergroupStoreRequest extends Request
{
	public $model = \App\Usergroup::class;

	public function authorize() {
		return auth()->guard('api')->user()->can('store', Usergroup::class);
	}

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
