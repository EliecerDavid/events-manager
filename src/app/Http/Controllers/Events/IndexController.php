<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        $events = $user->events;
        $enrolledEvents = $user->enrolledEvents;

        $allEvents = $events->merge($enrolledEvents);
        return EventResource::collection($allEvents);
    }
}
