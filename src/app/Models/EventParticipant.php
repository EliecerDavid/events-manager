<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventParticipant extends Pivot
{
    use HasFactory;

    public function adder(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'added_by',
            ownerKey: 'id'
        );
    }
}
