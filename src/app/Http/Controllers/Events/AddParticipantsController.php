<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventParticipantResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddParticipantsController extends Controller
{
    public function __invoke(Request $request, Event $event)
    {
        $validated = $request->validate([
            '*' => 'nullable|exists:users,id',
        ]);

        $event->participants()
            ->syncWithPivotValues(
                ids: $validated,
                values: ['added_by' => Auth::user()->id],
                detaching: false
            );

        return EventParticipantResource::collection($event->participants);
    }
}
