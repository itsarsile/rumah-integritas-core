<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_marks', function (Blueprint $table) {
            $table->id();
            $table->string('module'); // consumption, maintenance, agenda
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->date('date');
            $table->string('label')->nullable();
            $table->string('color', 20)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['module', 'division_id', 'date']);
            $table->index(['module', 'division_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_marks');
    }
};

