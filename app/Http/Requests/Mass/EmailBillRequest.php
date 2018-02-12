<?php

namespace App\Http\Requests\Mass;

use Zoomyboy\BaseRequest\Request;
use App\User;
use App\Notifications\EmailBillNotification;
use App\Member;
use App\Status;
use App\Queries\BillPdfQuery;

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

		$query = BillPdfQuery::members()->filterWays($ways);


		if ($this->includeFamilies) {
			$members = $query->get()->groupBy(function($m) {
				return $m->lastname.$m->zip.$m->city.$m->email;
			});

			$membersThatGetBill = $query->get()->groupBy(function($m) {
				return $m->lastname.$m->zip.$m->city;
			});

			foreach($members as $member) {
                $firstMember = $member->first();
                $firstMember->notify(new EmailBillNotification(
                    $firstMember,
                    $this->includeFamilies,
                    $this->deadline,
                    $membersThatGetBill[$firstMember->lastname.$firstMember->zip.$firstMember->city]
                ));

				if (!$this->updatePayments) {
					return;
				}

				foreach($membersThatGetBill[$firstMember->lastname.$firstMember->zip.$firstMember->city] as $paymentMember) {
					$paymentMember->payments()->where('status_id', '1')->get()->each(function($p) {
						$p->status()->associate(Status::find(2));
						$p->save();
					});
				}
			}

			return;
		}

		foreach($query->get() as $member) {
			$member->notify(new EmailBillNotification($member, $this->includeFamilies, $this->deadline, collect([$member])));

			if ($this->updatePayments) {
				$member->payments()->where('status_id', '1')->get()->each(function($p) {
					$p->status()->associate(Status::find(2));
					$p->save();
				});
			}
		}
	}
}
