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
        Schema::table("agenda_reports", function (Blueprint $table) {
            $table->string("title")->after('id');
            $table->string('request_code', 50)->unique();
            $table->date('date');
            $table->string('location');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->references('id')->on('users')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected']);
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
