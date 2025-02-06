<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_participant', function (Blueprint $table) {
            $table->id();

            $table->foreignId(column: 'participant_id')
                ->references(column: 'id')
                ->on(table: 'users');

            $table->foreignId(column: 'event_id')
                ->references(column: 'id')
                ->on(table: 'events');

            $table->foreignId(column: 'added_by')
                ->references(column: 'id')
                ->on(table: 'users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participant');
    }
};
