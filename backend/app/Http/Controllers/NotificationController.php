<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function userNotifications()
    {
        return NotificationResource::collection(Auth::user()->notifications);
    }
}
