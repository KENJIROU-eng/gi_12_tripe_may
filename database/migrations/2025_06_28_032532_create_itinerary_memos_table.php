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
        Schema::create('itinerary_memos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_id')->unique(); // 1つの旅程に1つのメモ
            $table->text('content')->nullable();
            $table->timestamps();

            $table->foreign('itinerary_id')->references('id')->on('itineraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerary_memos');
    }
};
