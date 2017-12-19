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

			$sumForCurrentPayment = $item->payments->reduce(function($zCarry, $zItem) use ($carry, $statusIds) {
				$zCarry = $zCarry ?: 0;
				if (in_array($zItem->status->id, $statusIds)) {
					return $zCarry + $zItem->amount;
				}
			});

			return $carry + $sumForCurrentPayment;
		});

		return number_format($totalSum / 100, 2, ',', '.');
	}

	public function enumNrs() {
		return '';
	}

	public function enumNames() {
		return '';
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
