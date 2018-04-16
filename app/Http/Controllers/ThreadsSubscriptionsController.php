<?php

namespace App\Http\Controllers;

use App\Thread;

class ThreadsSubscriptionsController extends Controller
{
    public function store($channelId, Thread $thread)
    {
        $thread->subscribe();
    }
}
