<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make reviewed_by nullable on PostgreSQL
        DB::statement("ALTER TABLE consumption_reports ALTER COLUMN reviewed_by DROP NOT NULL");
    }

    public function down(): void
    {
        // Revert to NOT NULL (will fail if NULLs exist in reviewed_by)
        DB::statement("ALTER TABLE consumption_reports ALTER COLUMN reviewed_by SET NOT NULL");
    }
};

