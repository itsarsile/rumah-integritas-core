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
            if (!Schema::hasColumn('agenda_reports', 'title')) {
                $table->string("title")->after('id');
            }
            if (!Schema::hasColumn('agenda_reports', 'request_code')) {
                $table->string('request_code', 50)->unique();
            }
            if (!Schema::hasColumn('agenda_reports', 'date')) {
                $table->date('date');
            }
            if (!Schema::hasColumn('agenda_reports', 'location')) {
                $table->string('location');
            }
            if (!Schema::hasColumn('agenda_reports', 'created_by')) {
                $table->foreignId('created_by')->references('id')->on('users');
            }
            if (!Schema::hasColumn('agenda_reports', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable();
            }
            if (!Schema::hasColumn('agenda_reports', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->references('id')->on('users');
            }
            if (!Schema::hasColumn('agenda_reports', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_reports', function (Blueprint $table) {
            // You can optionally remove columns here if needed
        });
    }
};