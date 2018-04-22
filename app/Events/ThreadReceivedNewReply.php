<?php

namespace App\Events;

use App\Reply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreadReceivedNewReply
{
    use Dispatchable, SerializesModels;

    /**
     * @var Reply
     */
    public $reply;

    /**
     * ThreadReceivedNewReply constructor.
     * @param $reply
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }
}
