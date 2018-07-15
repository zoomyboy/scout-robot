<?php

namespace App\Jobs;

use App\Member;
use App\Nami\Receiver\Member as MemberReceiver;
use App\Nami\Manager\Member as MemberManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use \App\Events\Import\MemberCreated;
use \App\Events\Import\MemberUpdated;

class SyncAllNamiMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filter;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filter = [])
    {
        $this->filter = $filter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MemberReceiver $receiver, MemberManager $manager)
    {
        $members = $receiver->all($this->filter);

        foreach($members as $i => $member) {
            if (Member::nami($member->id)->exists()) {
                $member = $manager->update($member->id);
                event(new MemberUpdated($member, $i+1, count($members)));
                continue;
            }

            $member = $manager->store($member->id);

            if ($member == null) {
                return false;
            }

            event(new MemberCreated($member, $i+1, count($members)));
        }
    }
}