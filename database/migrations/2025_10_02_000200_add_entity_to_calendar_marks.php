<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calendar_marks', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_id')->nullable()->after('date');
            $table->string('entity_type')->nullable()->after('entity_id');
            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::table('calendar_marks', function (Blueprint $table) {
            $table->dropIndex(['entity_type', 'entity_id']);
            $table->dropColumn(['entity_id', 'entity_type']);
        });
    }
};

