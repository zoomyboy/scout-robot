<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Zoomyboy\BaseRequest\Request;
use App\Member;

class MemberStoreRequest extends Request
{

	public $model = Member::class;

    public function authorize()
    {
		return auth()->guard('api')->user()->can('store', Member::class);
    }

	public function rules() {
		$ret = [
			'firstname' => 'required',
			'lastname' => 'required',
			'birthday' => 'required|date',
			'joined_at' => 'required|date',
			'address' => 'required',
			'zip' => 'required|numeric',
			'city' => 'required',
			'country' => 'required|exists:countries,id',
			'way' => 'required|exists:ways,id',
			'nationality' =>  'required|exists:nationalities,id'
		];

		if ($this->input('email')) {
			$ret['email'] = 'email';
		}

		return $ret;
	}
}
