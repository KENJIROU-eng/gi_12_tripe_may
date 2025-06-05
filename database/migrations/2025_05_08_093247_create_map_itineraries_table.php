<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('map_itineraries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('date_id');
            $table->string('place_name')->nullable();
            $table->string('destination');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('place_id')->nullable();
            $table->float('distance_km')->nullable();
            $table->string('duration_text')->nullable();

            $table->foreign('date_id')->references('id')->on('date_itineraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_itineraries');
    }
};
