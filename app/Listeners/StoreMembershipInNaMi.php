<?php

namespace App\Listeners;

use App\Events\MembershipCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Facades\NaMi\NaMiMembership;

class StoreMembershipInNaMi
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
     * @param  MembershipCreated  $event
     * @return void
     */
    public function handle(MembershipCreated $event)
    {
		NaMiMembership::store($event->model);
    }
}
