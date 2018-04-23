<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
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
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        // Inspect the body of the reply for username mentions
        preg_match_all('/\@([\w\-]+)/', $event->reply->body, $matches);

        $names = $matches[1];
        // And then for each mentioned user, notify them.
        User::whereIn('name', $names)
            ->get()
            ->each(function($user) use ($event){
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
