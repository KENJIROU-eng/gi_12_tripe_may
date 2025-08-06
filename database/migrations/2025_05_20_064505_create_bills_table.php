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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('itinerary_id');
            $table->unsignedBigInteger('user_pay_id');
            $table->string('name');
            $table->string('cost');
            $table->string('eachPay');
            $table->timestamps();

            $table->foreign('itinerary_id')->references('id')->on('itineraries')->onDelete('cascade');
            $table->foreign('user_pay_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
