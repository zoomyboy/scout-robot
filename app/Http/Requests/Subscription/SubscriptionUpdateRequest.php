<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use Zoomyboy\BaseRequest\Request;
use App\Subscription;

class SubscriptionUpdateRequest extends Request
{

	public $model = Subscription::class;

	public function rules() {
		$ret = [
			'title' => 'required',
			'amount' => 'required|numeric'
		];

		if (!is_null($this->fee)) {
			$ret['fee'] = 'exists:fees,id';
		}

		return $ret;
	}

	public function modifyFillables($fill = null) {
		$fill['amount'] = intVal($fill['amount']);

		return $fill;
	}
}
