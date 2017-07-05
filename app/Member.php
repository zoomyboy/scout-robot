<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
	public $fillable = ['firstname', 'lastname', 'nickname', 'other_country', 'birthday', 'joined_at', 'keepdata', 'sendnewspaper', 'address', 'further_address', 'zip', 'city', 'phone', 'mobile', 'business_phone', 'fax', 'email', 'email_parents'];

	//---------------------------------- Relations ----------------------------------
	public function country() {
		return $this->belongsTo(\App\Country::class);
	}

	public function gender() {
		return $this->belongsTo(\App\Gender::class);
	}

	public function region() {
		return $this->belongsTo(\App\Region::class);
	}

	public function confession() {
		return $this->belongsTo(\App\Confession::class);
	}
}
