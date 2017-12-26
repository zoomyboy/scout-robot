<?php

namespace App\Queries;

use App\Member;
use App\Payment;

class BillPdfQuery {
	public function handle($ways = [1,2]) {
		return Member::orderByRaw('lastname, firstname')->whereIn('way_id', $ways)
			->whereHas('payments', function($q) {
				return $q->whereIn('status_id', [1])->where('amount', '>', 0);
			})
		;
	}
}
