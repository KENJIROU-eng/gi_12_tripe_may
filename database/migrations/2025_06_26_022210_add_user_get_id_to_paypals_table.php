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
        Schema::table('paypals', function (Blueprint $table) {
            $table->unsignedBigInteger('user_get_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paypals', function (Blueprint $table) {
            $table->dropColumn('user_get_id');
        });
    }
};
