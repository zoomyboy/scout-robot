<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;

class UserDestroyRequest extends Request
{
	public $model = \App\User::class;

	public function customRules() {
		if(auth()->user()->id == $this->input('id')) {
			return ['id' => 'Du kannst dich nicht selber löschen!'];
		}
	}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required'
        ];
    }

	public function persist($user=null) {
		$user->delete();
	}
}
