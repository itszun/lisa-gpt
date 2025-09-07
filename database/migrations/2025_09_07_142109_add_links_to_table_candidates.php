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
            $table->date('date_interview')->nullable()->after('notified_at');
            $table->text('link_interview')->nullable()->after('date_interview');
            $table->date('date_contract')->nullable()->after('link_interview');
            $table->text('link_contract')->nullable()->after('date_contract');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn('date_interview');
            $table->dropColumn('link_interview');
            $table->dropColumn('date_contract');
            $table->dropColumn('link_contract');
        });
    }
};
