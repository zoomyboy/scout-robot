<?php

namespace App\Nami\Manager;

use App\Activity;
use App\Group;
use App\Member;
use App\Nami\Receiver\Membership as MembershipReceiver;
use App\Nami\Service;
use Carbon\Carbon;

class Membership {
    private $receiver;

    public function __construct(MembershipReceiver $receiver) {
        $this->receiver = $receiver;
    }

    public function pull($memberId) {
        $member = Member::nami($memberId)->first();

        $memberships = $this->receiver->all($memberId)->map(function($ms) use ($memberId) {
            return $this->receiver->single($memberId, $ms->id);
        })->filter(function($ms) {
            return Activity::nami($ms->taetigkeitId)->exists()
            && (!isset($ms->untergliederungId) || Activity::nami($ms->taetigkeitId)->first()->groups()->nami($ms->untergliederungId)->exists());
        })->each(function($ms) use ($member) {
            if(!$ms->aktivBis || Carbon::parse($ms->aktivBis)->isFuture()) {
                $member->memberships()->updateOrCreate(['nami_id' => $ms->id], [
                    'activity_id' => Activity::nami($ms->taetigkeitId)->first()->id,
                    'nami_id' => $ms->id,
                    'group_id' => isset($ms->untergliederungId)
                        ? Group::nami($ms->untergliederungId)->first()->id
                        : null,
                    'created_at' => Carbon::parse($ms->aktivVon)
                ]);
            } elseif(Carbon::parse($ms->aktivBis)->isPast()) {
                $member->memberships()->nami($ms->id)->delete();
            }
        });
    }
}
