<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Zoomyboy\BaseRequest\Request;
use App\Member;

class MemberDeleteRequest extends Request
{

	public $model = Member::class;

	public function rules() {
		return [
			'id' => 'required|numeric|exists:members,id'
		];
	}

	public function persist($model = null) {
		$model->delete();
	}
}
