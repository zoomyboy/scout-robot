<?php

/**
 * Sends a Password Reset Notification to a user that has just been created
 * by an admin.
 * That way, the new User can set his Password by himself.
 */

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class FirstUserPasswordNotification extends Notification
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
			->subject('['.config('app.name').'] Herzlich Willkommen bei '.config('app.name'))
            ->line('Du bekommst diese E-Mail, weil ein neues Benutzerkonto für dich eingerichtet wurde. Über den folgenden Link kannst du dein Passwort für deinen Account selber bestimmen:')
            ->action('Passwort festlegen', url(config('app.url').'/login#/first-password/'.$this->token));
    }
}
