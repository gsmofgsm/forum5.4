<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        // Given we have a thread
        $thread = create('App\Thread');

        // And the user subscribes to the thread
        $this->post($thread->path() . '/subscriptions');
        // $this->assertCount(1, $thread->subscriptions); // this is only temporarily for testing endpoints

        // Then, each time a new reply is left...
        $thread->addReply([
            'user_id' => ($user_id = 1),
            'body' => 'Some reply here'
        ]);

        // A notification should be prepared for the user.
//        $this->assertCount(1, auth()->user()->notifications);
    }
}