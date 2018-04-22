<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
        // given I have a user, JohnDoe, who is signed in.
        $john = create('App\User',['name' => 'JohnDoe']);
        $this->signIn($john);

        // And another user, JaneDoe.
        $jane = create('App\User',['name' => 'JaneDoe']);

        // If we have a thread
        $thread = create('App\Thread');

        // And JohnDoe replies and mentions @JaneDoe.
        $reply = make('App\Reply', [
            'body' => '@JaneDoe look at this. Also @FrankDoe.'
        ]);
        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        // Then, JaneDoe should be notified
        $this->assertCount(1, $jane->notifications);
    }
}
