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
        Schema::create('date_itineraries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_id');
            $table->date('date');

            $table->foreign('itinerary_id')->references('id')->on('itineraries')->onDelete('cascade');
            $table->unique(['itinerary_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date_itineraries');
    }
};
