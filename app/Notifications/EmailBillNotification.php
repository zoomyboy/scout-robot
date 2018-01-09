<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Zoomyboy\BetterNotifications\MailMessage;
use App\Member;
use App\Services\Pdf\Bill as BillPdfService;
use App\Collections\OwnCollection;
use App\Conf;
use Illuminate\Support\Facades\Blade;

class EmailBillNotification extends Notification
{
	use Queueable;

	public $member;
	public $family;
	public $deadline;
	public $config;
	public $members;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Member $member, $family, $deadline, $members)
    {
        $this->member = $member;
		$this->family = $family;
		$this->deadline = $deadline;
		$this->config = Conf::first();
		$this->members = $members;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
	}

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
	{
		$greeting = 'Sehr geehrte Familie '.$this->member->lastname.',';

		$body = $this->generateView($this->config->emailBillText, ['members' => $this->generateMemberString()]);

        $message = (new MailMessage)
			->greeting($greeting)
            ->line($body)
			->subject('Rechnung von '.Conf::first()->groupname)
			->salutation('Viele Grüße und gut Pfad Der Stammesvorstand')
			->heading(Conf::first()->emailHeading ?: '');

		$members = $this->family === "true"
			? Member::family($this->member)->get()->groupBy('lastname')
			: (new OwnCollection([$this->member]))->groupBy('lastname');

		$filename = ($this->family)
			? 'Rechnung für Familie '.$this->member->lastname.'.pdf'
			: 'Rechnung für '.$this->member->firstname.' '.$this->member->lastname.'.pdf';
		$service = new BillPdfService($members, ['deadline' => $this->deadline, 'family' => $this->family]);
		$service->handle($filename);

		$message->attach(public_path('pdf/'.$filename));

		return $message;
    }

	public function generateMemberString() {
		$members = $this->members;

		if ($members->count() == 0) {
			return '';
		}

		$last = $members->pop();

		return implode(', ', $members->map(function($m) {return $m->firstname.' '.$m->lastname;})->toArray())
			.(($members->count()) ? ' und ' : '')
			.$last->firstname.' '.$last->lastname;
	}

	private function generateView($str, $params) {
		file_put_contents(resource_path('views/temp/string.blade.php'), $str);
		return (string) view()->make('temp.string', $params);
	}

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
