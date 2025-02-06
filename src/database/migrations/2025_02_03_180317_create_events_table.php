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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->string(column: 'title');
            $table->text(column: 'description')->nullable();
            $table->string(column: 'location')->nullable();
            $table->date(column: 'start_date')->nullable();
            $table->date(column: 'end_date')->nullable();

            $table->foreignId(column: 'created_by')
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
        Schema::dropIfExists('events');
    }
};
