<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function only_members_can_add_avatars()
    {
        $this->withExceptionHandling()->json('POST', 'api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    function a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling()->signIn();
        $this->json('POST', 'api/users/' . auth()->id() . '/avatar', [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);
    }
}
