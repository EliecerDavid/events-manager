<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventParticipantResource;
use App\Models\Event;

class IndexParticipantsController extends Controller
{
    public function __invoke(Event $event)
    {
        $participants = $event->participants;
        return EventParticipantResource::collection($participants);
    }
}
