<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_trips', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('bus_route_id')->constrained('bus_routes')->cascadeOnDelete();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at')->nullable();
            $table->decimal('base_fare', 10, 2)->default(0);
            $table->string('status')->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['bus_route_id', 'departure_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_trips');
    }
};
