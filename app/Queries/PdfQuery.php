<?php

namespace App\Queries;

use App\Member;

class PdfQuery {

    public $query = null;

    public function get() {
        return $this->query->get();
    }

    public function filterWays($ways) {
        $this->query = $this->query->whereIn('way_id', $ways);

        return $this;
    }

    public static function members() {
        $temp = new static;

		$temp->query = Member::orderByRaw('lastname, firstname')
			->whereHas('payments', function($q) use ($temp) {
				return $q->whereIn('status_id', $temp->statuses)->whereHas('subscription', function($s) {
					return $s->where('amount', '>', 0);
				});
			})
		;

        return $temp;
    }
}
