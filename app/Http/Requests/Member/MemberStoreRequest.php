<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Zoomyboy\BaseRequest\Request;
use App\Member;
use App\Group;
use Illuminate\Validation\Rule;

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
			'nationality' => 'required|exists:nationalities,id',
			'activity' => 'required|exists:activities,id',
		];

		$ret['group'] = [
			'required',
			Rule::in(Group::whereHas('activities', function($q) {
				return $q->where('id', $this->activity);
			})->get()->pluck('id')->toArray())
		];

		if ($this->input('email')) {
			$ret['email'] = 'email';
		}

		return $ret;
	}
}
