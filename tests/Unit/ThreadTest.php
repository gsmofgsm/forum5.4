<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }
    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies );
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $thread = create('App\Thread');
        $this->assertEquals('/threads/'.$thread->channel->slug.'/'.$thread->id, $thread->path());
    }

    /** @test */
    public function a_thread_has_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread' );

        $this->get('threads?by=JohnDoe')
            ->assertSee(($threadByJohn->title))
            ->assertDontSee($threadNotByJohn->title);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        // Given we have a thread

        // When the user subscribes to the thread
        $this->thread->subscribe($userId = 1);

        // Then we should be able to fetch all threads that the user has subscribed to
        $this->assertEquals(
            1, $this->thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        // Given we have a thread

        // And user subscribes to the thread
        $this->thread->subscribe($userId = 1);

        $this->thread->unsubscribe($userId);

        $this->assertEmpty($this->thread->subscriptions);
    }

    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();
        $this->assertTrue($this->thread->hasUpdatesFor(auth()->user()));

        auth()->user()->read($this->thread);
        $this->assertFalse($this->thread->hasUpdatesFor(auth()->user()));
    }

    /** @test */
    function a_thread_records_each_visit()
    {
        /** @var Thread $thread */
        $thread = make('App\Thread', ['id' => 1]);
        $thread->visits()->reset();
        $this->assertSame(0, $thread->visits()->count());
        $thread->visits()->record();
        $this->assertEquals(1, $thread->visits()->count());
        $thread->visits()->record();
        $this->assertEquals(2, $thread->visits()->count());
    }
}
