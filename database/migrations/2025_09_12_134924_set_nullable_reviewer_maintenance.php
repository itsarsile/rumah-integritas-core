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
        Schema::table('maintenance_reports', function (Blueprint $table) {
        $table->dropForeign(['reviewed_by']); 
        $table->foreignId('reviewed_by')
              ->nullable()
              ->change(); 
        $table->foreign('reviewed_by')->references('id')->on('users');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
