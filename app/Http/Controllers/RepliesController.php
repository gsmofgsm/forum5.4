<?php

namespace App\Http\Controllers;

use App\Http\Forms\CreatePostForm;
use App\Reply;
use App\Inspections\Spam;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(1);
    }

    public function store($channelId, Thread $thread, CreatePostForm $form)
    {
        return $form->persist($thread);
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try{

            $this->validate(request(), [
                'body' => 'required|spamfree'
            ]);

            $reply->update(request(['body']));

        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->expectsJson()){
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
