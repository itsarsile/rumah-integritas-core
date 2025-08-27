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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_report_id')->constrained('audit_reports')->cascadeOnDelete();
            $table->string('room_code', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'archived', 'closed'])->default('active');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            
            $table->index(['audit_report_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
