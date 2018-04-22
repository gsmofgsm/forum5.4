<?php

namespace App\Http\Controllers;

use App\Http\Forms\CreatePostForm;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\User;

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
        $reply = $form->persist($thread);
        // Inspect the body of the reply for username mentions
        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);

        $names = $matches[1];
        // And then for each mentioned user, notify them.
        foreach($names as $name){
            $user = User::whereName($name)->first();

            if($user) {
                $user->notify(new YouWereMentioned($reply));
            }
        }
        return $reply;
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
