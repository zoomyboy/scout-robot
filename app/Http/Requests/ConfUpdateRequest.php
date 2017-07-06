<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;

class ConfUpdateRequest extends Request
{
	public $model = \App\Conf::class;
	public $right = 'user';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [];
    }
}
