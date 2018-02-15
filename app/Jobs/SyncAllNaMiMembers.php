<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Facades\NaMi\NaMiMember;
use App\Facades\NaMi\NaMiMembership;
use App\Member;
use \App\Events\Import\MemberCreated;
use \App\Events\Import\MemberUpdated;

class SyncAllNaMiMembers implements ShouldQueue
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
    public function handle()
    {
        $members = NaMiMember::all($this->filter);

        foreach($members as $i => $member) {
            $namiMember = NaMiMember::single($member->id);

            if (Member::nami($namiMember->id)->exists()) {
                $member = NaMiMember::update(Member::nami($namiMember->id)->first(), $namiMember);
                event(new MemberUpdated($member, $i+1, count($members)));
                continue;
            }

            $member = NaMiMember::importMember($namiMember);

            if ($member == null) {
                return false;
            }

            NaMiMember::importMemberships($member);

            event(new MemberCreated($member, $i+1, count($members)));
        }
    }
}
