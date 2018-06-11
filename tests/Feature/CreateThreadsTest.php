<?php

namespace Tests\Feature;

use App\Rules\Recaptcha;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            $m = \Mockery::mock(Recaptcha::class);
            $m->shouldReceive('passes')->andReturn(true);
            return $m;
        });
    }

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads', [])
            ->assertRedirect('/login');
    }

    /** @test */
    function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();
        $this->withExceptionHandling()->signIn($user);
        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }

    /** @test */
    public function a_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        $this->actingAs(factory('App\User')->create());

        // when we hit the endpoint to create a new thread
        $thread = factory('App\Thread')->make();
        $response = $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);

        // Then, when we visit the thread page.
        $this->get($response->headers->get('Location'))
        // We should see the new thread
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertStatus(422);
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertStatus(422);
    }

    /** @test */
    function a_thread_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertStatus(422);
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertStatus(422);

        $this->publishThread(['channel_id' => 999])
            ->assertStatus(422);
    }

    /** @test */
    function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        create('App\Thread', [], 2); // 2 random threads to make id's not from 1

        $thread = create('App\Thread', ['title' => 'Foo Title']);

        $this->assertEquals('foo-title-' . $thread->id, $thread->fresh()->slug);

        $thread2 = $this->postJson('/threads', $thread->toArray() + [ 'g-recaptcha-response' => 'token' ])->json();

        $this->assertEquals('foo-title-' . $thread2['id'], $thread2['slug']);

        $thread3 = $this->postJson('/threads', $thread->toArray() + [ 'g-recaptcha-response' => 'token' ])->json();

        $this->assertEquals('foo-title-' . $thread3['id'], $thread3['slug']);
    }

    /** @test */
    function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Some Title 24']);

        $this->post('/threads', $thread->toArray() + [ 'g-recaptcha-response' => 'token' ] );

        $this->assertTrue(Thread::whereSlug('some-title-24-2')->exists());
    }

    /** @test */
    function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed'
        ])->assertSessionHasErrors('body');
        $this->patch($thread->path(), [
            'body' => 'Changed'
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_can_be_updated()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body' => 'Changed body.'
        ]);
        $this->assertEquals('Changed', $thread->fresh()->title);
        $this->assertEquals('Changed body.', $thread->fresh()->body);
    }

    /** @test */
    public function guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $response = $this->delete($thread->path());
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authorised_user_can_delete_a_thread()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id'=>auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class( $thread )
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class( $reply )
        ]);
    }

    /** @test */
    public function unauthorised_user_can_not_delete_a_thread()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->withExceptionHandling()->delete($thread->path())->assertStatus(403);
    }

    protected function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);
        return $this->json('post', '/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);
    }
}
