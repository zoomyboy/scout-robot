<?php

namespace App\Jobs;

use App\Member;
use Carbon\Carbon;
use App\Nami\Service;
use Illuminate\Bus\Queueable;
use App\Events\MemberCancelled;
use App\Nami\Interfaces\UserResolver;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CancelNamiMember implements ShouldQueue
{
    private $member;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle(UserResolver $user)
    {
        if ($this->member->isNami()) {
            $response = app('nami')->post('/ica/rest/nami/mitglied/filtered-for-navigation/mglschaft-beenden?gruppierung='.$user->getGroup(), [
                'id' => $this->member->nami_id,
                'isConfirmed' => 'true',
                'beendenZumDatum' => Carbon::now()->subDays(1)->format('Y-m-d').' 00:00:00'
            ]);
            if ($response->get('success') === true && $response->get('responseType') == 'OK') {
                $this->member->delete();
                event(new MemberCancelled($this->member->id));
            }
        } else {
            $this->member->delete();
            event(new MemberCancelled($this->member->id));
        }
    }
}
