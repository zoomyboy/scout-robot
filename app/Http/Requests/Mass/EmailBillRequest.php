<?php

namespace App\Http\Requests\Mass;

use Zoomyboy\BaseRequest\Request;
use App\User;
use App\Notifications\EmailBillNotification;
use App\Member;

class EmailBillRequest extends Request
{
	public $model = \App\User::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'includeFamilies' => 'required|boolean',
			'wayEmail' => 'required|boolean',
			'wayPost' => 'required|boolean',
			'updatePayments' => 'required|boolean',
		];
    }

	public function persist($model = null) {
		$ways = [];
		if ($this->wayEmail) {$ways[] = 1;}
		if ($this->wayPost) {$ways[] = 2;}

		$query = Member::whereIn('way_id', $ways);

		foreach($query->get() as $member) {
			$member->notify(new EmailBillNotification($member));
		}
	}
}
