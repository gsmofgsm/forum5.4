<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $reply = create('App\Reply');

        // If I post to a "favorite" endpoint
        $this->signIn();
        $this->post('/replies/' . $reply->id . '/favorites');

        // It should be recorded in the database
        $this->assertCount(1, $reply->favorites);
    }
}
