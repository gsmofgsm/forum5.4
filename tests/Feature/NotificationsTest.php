<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();
        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        // Then, each time a new reply is left...
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply here'
        ]);

        // A notification should be prepared for the user.
        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_notification_is_not_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_by_the_current_user()
    {
        $this->signIn();
        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        // Then, each time a new reply is left...
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

        // A notification should be prepared for the user.
        $this->assertCount(0, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_clear_a_notification()
    {
        $this->signIn();
        $thread = create('App\Thread')->subscribe();

        // Then, each time a new reply is left...
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply here'
        ]);
        $this->assertCount(1, auth()->user()->unreadNotifications);
        $notificationId = auth()->user()->unreadNotifications->first()->id;

        $username = auth()->user()->name;
        $this->delete("/profiles/{$username}/notifications/{$notificationId}");

        // A notification should be prepared for the user.
        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }
}
