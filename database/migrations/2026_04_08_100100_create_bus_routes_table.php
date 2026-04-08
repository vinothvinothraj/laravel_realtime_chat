<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_routes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('bus_operator_id')->constrained('bus_operators')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('origin');
            $table->string('destination');
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_routes');
    }
};
