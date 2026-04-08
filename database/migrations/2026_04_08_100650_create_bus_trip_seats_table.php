<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_trip_seats', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('bus_trip_id')->constrained('bus_trips')->cascadeOnDelete();
            $table->foreignId('bus_seat_id')->constrained('bus_seats')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->string('status')->default('available');
            $table->dateTime('held_until')->nullable();
            $table->timestamps();

            $table->unique(['bus_trip_id', 'bus_seat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_trip_seats');
    }
};
