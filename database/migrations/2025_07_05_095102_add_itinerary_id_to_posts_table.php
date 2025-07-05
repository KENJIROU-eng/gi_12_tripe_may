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
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('itinerary_id')->nullable()->after('user_id');
            $table->foreign('itinerary_id')->references('id')->on('itineraries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['itinerary_id']); // 外部キー制約を先に削除
            $table->dropColumn('itinerary_id');     // カラムを削除
        });
    }
};
