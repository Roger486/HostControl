<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function userNotifications()
    {
        return response()->json([
            'notifications' => Auth::user()->notifications
        ]);
    }
}
