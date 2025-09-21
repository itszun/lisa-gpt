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
        Schema::table('candidates', function (Blueprint $table) {
            $table->string("chat_user_id")->nullable();
            $table->string("session_id")->nullable();
            $table->timestamp("last_feed_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn("chat_user_id");
            $table->dropColumn("session_id");
            $table->dropColumn("last_feed_at");
        });
    }
};
