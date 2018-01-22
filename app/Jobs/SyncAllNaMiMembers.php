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

class SyncAllNaMiMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$members = NaMiMember::all();

		foreach($members as $member) {
			$namiMember = NaMiMember::single($member->id);

			if (Member::nami($namiMember->id)->first() != null) {
				NaMiMember::update(Member::nami($namiMember->id)->first(), $namiMember);
				return;
			}

			$member = NaMiMember::importMember($namiMember);	

			if ($member == null) {
				return false;
			}

			NaMiMember::importMemberships($member);
		}
    }
}
