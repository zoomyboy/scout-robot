<?php

namespace App\Http\Requests;

use Zoomyboy\BaseRequest\Request;
use Illuminate\Validation\Rule;
use App\Facades\NaMi\NaMiMember;

class NaMiGetRequest extends Request
{
	public $model = \App\Member::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

	public function persist($model = null) {
		$members = NaMiMember::all();

		foreach($members as $member) {
			NaMiMember::importMember(NaMiMember::single($member->id));	
		}
	}
}
