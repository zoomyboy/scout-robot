<?php

namespace App\Collections;

/**
 * @deprecated
 */

class OwnCollection extends \Illuminate\Database\Eloquent\Collection {
	public function enumAmount() {
		foreach($this->items as $member) {
			foreach($member->zahlungRechnungData as $payment) {
				return '';
			}
		}
	}

	public function totalAmount($statusIds = []) {
		$totalSum = $this->reduce(function($carry, $item) use ($statusIds) {
			$carry = $carry ?: 0;

			$sumForCurrentPayment = $item->payments()->whereIn('status_id', $statusIds)->get()->reduce(function($zCarry, $zItem) use ($carry) {
				$zCarry = $zCarry ?: 0;
				return $zCarry + $zItem->amount;
			});

			return $carry + $sumForCurrentPayment;
		});

		return number_format($totalSum / 100, 2, ',', '.');
	}

	public function enumNrs() {
		return '';
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

	public function pluckRec($plucker, $return = false) {
		if($return === false) {$return = collect([]);}
		$keys = explode('.', $plucker);
		$key = array_shift($keys);

		foreach($this->items as $item) {
			if ($key == '_') {
				$return = $return->merge($item->pluckRec(implode('.', $keys)));
			} elseif(is_string($item) || is_int($item) || is_numeric($item)) {
				$return = $return->push($item);
			} elseif(method_exists($item, $key)) {
				$collection = $item->{$key}()->get();
				$return = $return->merge($collection->pluckRec(implode('.', $keys)));
			} elseif(is_array($item)) {
				if (array_key_exists($key, $item)) {
					$collection = new self([0 => $item[$key]]);
					$return = $return->merge($collection->pluckRec(implode('.', $keys)));
				}
			} elseif($item->{$key} != null) {
				$collection = new self([0 => $item->{$key}]);
				$return = $return->merge($collection->pluckRec(implode('.', $keys)));
			}
		}
		return new self($return->values());
	}
	public function enum() {
		return enum($this->items);
	}
}
