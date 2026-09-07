<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->foreignId('waypoint_id')
                ->nullable()
                ->after('category_id')
                ->constrained('waypoints')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {});
    }
};
