<?php

namespace App\Collections;

/**
 * @deprecated
 */

class OwnCollection extends \Illuminate\Database\Eloquent\Collection {
	public function totalAmount($statusIds = []) {
		$totalSum = $this->reduce(function($carry, $item) use ($statusIds) {
			$carry = $carry ?: 0;

			$sumForCurrentPayment = $item->payments()->whereIn('status_id', $statusIds)->get()->reduce(function($zCarry, $zItem) use ($carry) {
				$zCarry = $zCarry ?: 0;
				return $zCarry + $zItem->subscription->amount;
			});

			return $carry + $sumForCurrentPayment;
		});

		return number_format($totalSum / 100, 2, ',', '.');
	}

	public function enumNames() {
		switch($this->count()) {
			case 0; return '';
			case 1: return $this->items[0]->firstname.' '.$this->items[0]->lastname;
			default:
				$items = $this->items;
				$last = array_pop($items);
				return implode(', ', array_map(function($item) { return $item->firstname.' '.$item->lastname; }, $items)).' und '.$last->firstname.' '.$last->lastname;
		}
	}
}
