<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        return (new MailNotification)
			->subject('['.config('app.name').'] Ihre Anfrage zum zurücksetzen des Passworts')
            ->line('Du bekommst diese E-Mail,  weil wir eine Anfrage zum zurücksetzen deines Passworts bekommen haben. Folge diesem Link, um dein Passwort zurückzusetzen:')
            ->action('Passwort zurücksetzen', url(config('app.url').'/login#/reset-password/'.$this->token))
            ->line('Wenn du keine Anfrage zum zurücksetzen deines Passworts gestellt hast, kannst du diese Nachricht ignorieren.');
    }
}
