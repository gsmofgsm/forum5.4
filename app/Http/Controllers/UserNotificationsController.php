<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy(User $user, $notificationId)
    {
        $user->notifications()->find($notificationId)->markAsRead();
    }
}
