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
        Schema::create('consumption_reports', function (Blueprint $table) {
            $table->id();
            $table->string('request_code', 50)->unique();
            $table->string('request_title');
            $table->date('event_request_date');
            $table->integer('audience_count');
            $table->text('email');
            $table->text('description')->nullable();
            $table->foreignId('consumption_type_id')->references('id')->on('consumption_types');
            $table->foreignId('division_id')->references('id')->on('divisions');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->references('id')->on('users')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumption_reports');
    }
};
