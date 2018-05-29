<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestRepliesController extends Controller
{
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);
//        abort_if($reply->thread->user_id != auth()->id(), 403);
        $reply->thread->update(['best_reply_id' => $reply->id]);
    }
}
