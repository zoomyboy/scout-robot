<?php

namespace App\Listeners;

use App\Events\MemberCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\NaMi\NaMiMember;

class StoreMemberInNaMi
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MemberCreated  $event
     * @return void
     */
    public function handle(MemberCreated $event)
    {
		if (!is_null($event->member->nami_id)) {
			return;
		}

		if (!$event->config->namiEnabled) {
			return;
		}

		$id = NaMiMember::store($event->member);
		if (is_numeric($id)) {
			$event->member->nami_id = $id;
			$event->member->save();
		}

		// @TODO Throw exception when not inserted
    }
}
