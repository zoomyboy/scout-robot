<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use Zoomyboy\BaseRequest\Request;
use App\Subscription;

class SubscriptionStoreRequest extends Request
{

	public $model = Subscription::class;

	public function rules() {
		return [
			'title' => 'required',
			'amount' => 'required|integer'
		];
	}
}
