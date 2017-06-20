<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\PasswordResetNotification;

class User extends Authenticatable
{
	use Notifiable;

	//--------------------------------- Boilerplate ---------------------------------
	protected $fillable = [
		'name', 'email', 'password'
	];

	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * Send the password reset notification.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new PasswordResetNotification($token));
	}

	//---------------------------------- Relations ----------------------------------
	public function usergroup() {
		return $this->belongsTo(\App\UserGroup::class);
	}
}
