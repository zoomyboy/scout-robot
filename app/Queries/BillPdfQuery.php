<?php

namespace App\Queries;

use App\Member;
use App\Payment;

class BillPdfQuery {

    public $query = null;

    public static function singleMember() {
        $q = new static;

		$q->query = Member::orderByRaw('lastname, firstname')
			->whereHas('payments', function($q) {
				return $q->whereIn('status_id', [1])->whereHas('subscription', function($s) {
					return $s->where('amount', '>', 0);
				});
			})
		;

        return $q;
    }

    public function filterWays($ways) {
        $this->query = $this->query->whereIn('way_id', $ways);

        return $this;
    }

    public function get() {
        return $this->query->get();
    }
}
