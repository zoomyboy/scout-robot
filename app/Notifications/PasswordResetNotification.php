<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Zoomyboy\BetterNotifications\MailMessage;

class PasswordResetNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;
	public $user;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token, $user)
    {
        $this->token = $token;
		$this->user = $user;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage($this->user))
			->subject('['.config('app.name').'] Ihre Anfrage zum zurücksetzen des Passworts')
			->line('Du bekommst diese E-Mail,  weil wir eine Anfrage zum zurücksetzen deines Passworts bekommen haben.')
			->line('Folge diesem Link, um dein Passwort zurückzusetzen:')
            ->action(url(config('app.url').'/login#/reset-password/'.$this->token), 'Passwort zurücksetzen')
            ->line('Wenn du keine Anfrage zum zurücksetzen deines Passworts gestellt hast, kannst du diese Nachricht ignorieren.');
    }
}
