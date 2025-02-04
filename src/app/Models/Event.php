<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'created_by',
            ownerKey: 'id'
        );
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'event_participant',
            foreignPivotKey: 'event_id',
            relatedPivotKey: 'participant_id'
        )->withPivot(columns: ['added_by'])
        ->using(EventParticipant::class);
    }
}
