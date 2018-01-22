<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Member;
use App\Facades\NaMi\NaMiMember;
use App\Facades\NaMi\NaMiMembership;

class StoreNaMiMember implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $member;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$id = NaMiMember::store($this->member);
		$firstMembership = NaMiMembership::all($id)[0];

		$this->member->memberships->first()->update(['nami_id' => $firstMembership->id]);

		if (is_numeric($id)) {
			$this->member->nami_id = $id;
			$this->member->save();
		}
    }
}
