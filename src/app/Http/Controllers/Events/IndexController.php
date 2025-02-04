<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;

class IndexController extends Controller
{
    public function __invoke()
    {
        $events = Event::all();
        return EventResource::collection($events);
    }
}
