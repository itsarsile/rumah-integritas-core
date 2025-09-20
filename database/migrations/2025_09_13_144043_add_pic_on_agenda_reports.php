<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("agenda_reports", function (Blueprint $table) {
            if (!Schema::hasColumn('agenda_reports', 'pic_id')) {
                $table->foreignId('pic_id')->nullable()->references('id')->on('users');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('agenda_reports', function (Blueprint $table) {
            if (Schema::hasColumn('agenda_reports', 'pic_id')) {
                $table->dropForeign(['pic_id']);
                $table->dropColumn('pic_id');
            }
        });
    }
};
