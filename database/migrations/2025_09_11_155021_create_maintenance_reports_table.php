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
        Schema::create('maintenance_reports', function (Blueprint $table) {
            $table->id();
            $table->string('request_code', 50)->unique();
            $table->foreignId('asset_type_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->references('id')->on('users')->nullable();
            $table->foreignId('division_id')->references('id')->on('divisions');
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_reports');
    }
};
