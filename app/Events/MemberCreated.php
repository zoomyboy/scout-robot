<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Member;
use App\Conf;

class MemberCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	public $member;
	public $config;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Member $member)
    {
		$this->member = $member;
		$this->config = Conf::first();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
