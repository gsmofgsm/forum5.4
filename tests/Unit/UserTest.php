<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create('App\User');
        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    function a_user_can_determine_their_avatar_path()
    {
        /** @var User $user */
        $user = create('App\User');
        $this->assertEquals('avatars/default.jpg', $user->avatar());
        $user->avatar_path = 'avatars/me.jpg';
        $this->assertEquals('avatars/me.jpg', $user->avatar());
    }
}
