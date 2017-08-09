<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\PasswordResetNotification;
use App\Notifications\FirstUserPasswordNotification;
use App\Notifications\FirstUserTokenNotification;

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
		$this->notify(new PasswordResetNotification($token, $this));
	}

	/**
	 * Send a  Password notification to the user when he was created
	 *
	 * @param string $password the new Password that has been set by the creator
	 */
	public function sendPasswordAfterCreationNotification($password) {
		$this->notify(new FirstUserPasswordNotification($password, $this));
	}

	/**
	 * Send a Link to the created user to set his passwort by themself
	 *
	 * @param string $token Reset-Token of the new user
	 */
	public function sendTokenAfterCreationNotification($token) {
		$this->notify(new FirstUserTokenNotification($token, $this));
	}

	//---------------------------------- Relations ----------------------------------
	public function usergroup() {
		return $this->belongsTo(\App\Usergroup::class);
	}

	//------------------------------------- UI --------------------------------------
	public function hasRight($key) {
		return $this->usergroup->rights()->where('key', $key)->first() != null;
	}
}
