<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class MailNotification extends MailMessage
{
    /**
     * The Markdown template to render (if applicable).
     *
     * @var string|null
     */
    public $markdown = 'mails.markdown';
}
