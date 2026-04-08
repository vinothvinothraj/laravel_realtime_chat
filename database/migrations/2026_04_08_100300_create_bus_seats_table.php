<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_seats', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->string('seat_number');
            $table->unsignedInteger('row_number')->default(1);
            $table->string('seat_position')->nullable();
            $table->string('status')->default('available');
            $table->boolean('is_window')->default(false);
            $table->boolean('is_aisle')->default(false);
            $table->timestamps();

            $table->unique(['bus_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_seats');
    }
};
