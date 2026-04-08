<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('bus_operator_id')->constrained('bus_operators')->cascadeOnDelete();
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->string('bus_type');
            $table->unsignedInteger('seat_capacity')->default(0);
            $table->json('seat_layout')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
