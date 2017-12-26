<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Zoomyboy\BaseRequest\Request;
use App\Member;

class MemberUpdateRequest extends Request
{

	public $model = Member::class;

    public function authorize()
    {
		return auth()->guard('api')->user()->can('update', $this->route('member'));
    }

	public function rules() {
		$ret = [
			'firstname' => 'required',
			'lastname' => 'required',
			'gender' => 'required',
			'birthday' => 'required|date',
			'joined_at' => 'required|date',
			'address' => 'required',
			'zip' => 'required|numeric',
			'city' => 'required',
			'country' => 'required|exists:countries,id',
			'region' => 'required|exists:regions,id',
			'way' => 'required|exists:ways,id',
		];

		if ($this->input('email')) {
			$ret['email'] = 'email';
		}

		return $ret;
	}
}
