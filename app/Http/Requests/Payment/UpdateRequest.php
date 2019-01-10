<?php

namespace App\Http\Requests\Payment;

use Zoomyboy\BaseRequest\Request;
use Illuminate\Validation\Rule;
use App\Status;
use App\Subscription;
use App\Payment;

class UpdateRequest extends Request {
	public $model = \App\Payment::class;

	public function rules() {
		return [
			'nr' => ['required', 'numeric', Rule::unique('payments')->where(function($query) {
				return $query
					->where('member_id', $this->route('member')->id)
					->where('id', '!=', $this->route('payment')->id);
			})],
			'status_id' => 'required|exists:statuses,id',
			'subscription_id' => 'required|exists:subscriptions,id',
		];
	}

	public function messages() {
		return [
			'nr.unique' =>  'Dieses Jahr existiert bereits'
		];
	}

    public function persist($model = null) {
        $model->update($this->only(['subscription_id', 'nr', 'status_id']));

        return [
            'payment' => $model,
            'strikes' => $this->route()->member->strikes
        ];
    }
}
