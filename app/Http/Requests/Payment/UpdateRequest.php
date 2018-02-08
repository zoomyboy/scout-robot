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
			'status' => 'required|exists:statuses,id',
			'subscription' => 'required|exists:subscriptions,id',
		];
	}

	public function messages() {
		return [
			'nr.unique' =>  'Dieses Jahr existiert bereits'
		];
	}

    public function persist($model = null) {
        $model->fill(['nr' => $this->nr]);

        $model->status()->associate(Status::find($this->status));
        $model->subscription()->associate(Subscription::find($this->subscription));
        $model->save();

        return $model;
    }
}
