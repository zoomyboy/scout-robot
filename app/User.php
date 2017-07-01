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
		return $this->belongsTo(\App\Usergroup::class);
	}

	//----------------------------------- Setters -----------------------------------
	/**
	 * Hash the User's Password on insertion
	 *
	 * @param string $password The unhashed password
	 */
	public function setPasswordAttribute($password) {
		$this->attributes['password'] = bcrypt($password);
	}

	//------------------------------------- UI --------------------------------------
	public function hasRight($key) {
		return $this->usergroup->rights()->where('key', $key)->first() != null;
	}
}
