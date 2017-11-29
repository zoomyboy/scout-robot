<?php

namespace App\Http\Requests\Payment;

use Illuminate\Validation\Rule;
use Zoomyboy\BaseRequest\Request;

class PaymentUpdateRequest extends Request {
	public $model = \App\Payment::class;

	public function rules() {
		return [
			'nr' => ['required', 'numeric', Rule::unique('payments')->where(function($query) {
				return $query
					->where('member_id', $this->member)
					->where('id', '!=', $this->route('payment')->id);
			})],
			'status' => 'required|exists:statuses,id',
			'member' => 'required|exists:members,id',
			'amount' => 'required|regex:/^[0-9]+,[0-9]+$/'
		];
	}

	public function messages() {
		return [
			'amount.regex' => 'Der Betrag muss das Format "99,99" haben',
			'nr.unique' =>  'Dieses Jahr existiert bereits'
		];
	}

	public function modifyFillables($fill = null) {
		$fill['amount'] = str_replace(',', '.', $fill['amount']) * 100;

		return $fill;
	}
}
