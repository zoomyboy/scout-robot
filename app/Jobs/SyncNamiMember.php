<?php

namespace App\Jobs;

use App\Member;
use Illuminate\Bus\Queueable;
use \App\Events\Import\MemberCreated;
use \App\Events\Import\MemberUpdated;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Nami\Manager\Member as MemberManager;
use App\Nami\Receiver\Member as MemberReceiver;

class SyncNamiMember implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $percent;
    public $memberId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($memberId, $percent)
    {
        $this->percent = $percent;
        $this->memberId = $memberId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MemberReceiver $receiver, MemberManager $manager)
    {
        if (Member::nami($this->memberId)->exists()) {
            $member = $manager->pull($this->memberId);
            event(new MemberUpdated($member, $this->percent));
            return;
        }

        $member = $manager->pull($this->memberId);

        if ($member == null) {
            return false;
        }

        event(new MemberCreated($member, $this->percent));
    }
}
