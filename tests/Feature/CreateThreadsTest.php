<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

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
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        $this->actingAs(factory('App\User')->create());

        // when we hit the endpoint to create a new thread
        $thread = factory('App\Thread')->make();
        $response = $this->post('/threads', $thread->toArray());

        // Then, when we visit the thread page.
        $this->get($response->headers->get('Location'))
        // We should see the new thread
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
