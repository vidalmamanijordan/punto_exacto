<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_waypoint_id')
                ->constrained('waypoints')
                ->cascadeOnDelete();
            $table->foreignId('to_waypoint_id')
                ->constrained('waypoints')
                ->cascadeOnDelete();
            $table->timestamps();
            /*
            |------------------------------------------------------------------
            | Distancia en metros entre ambos waypoints.
            | Se puede calcular automáticamente (fórmula Haversine) o
            | ingresar manualmente si se conoce la distancia real caminada.
            |------------------------------------------------------------------
            */
            $table->decimal('distance', 8, 2);
            $table->boolean('is_bidirectional')->default(true);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paths');
    }
};
