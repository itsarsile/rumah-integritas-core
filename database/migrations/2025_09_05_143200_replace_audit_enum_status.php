<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Drop old constraint
        DB::statement("ALTER TABLE audit_reports DROP CONSTRAINT IF EXISTS audit_reports_status_check");

        // 2. Map old values to new ones BEFORE adding new constraint
        DB::statement("UPDATE audit_reports SET status = 'pending' WHERE status IN ('draft', 'in_progress')");
        DB::statement("UPDATE audit_reports SET status = 'accepted' WHERE status IN ('completed', 'published')");

        // 3. Add the new constraint
        DB::statement("ALTER TABLE audit_reports ADD CONSTRAINT audit_reports_status_check CHECK (status IN ('pending', 'rejected', 'accepted'))");

        // 4. Set the new default
        DB::statement("ALTER TABLE audit_reports ALTER COLUMN status SET DEFAULT 'pending'");
    }

    public function down(): void
    {
        // 1. Drop new constraint
        DB::statement("ALTER TABLE audit_reports DROP CONSTRAINT IF EXISTS audit_reports_status_check");

        // 2. Map values back (approximate reversal)
        DB::statement("UPDATE audit_reports SET status = 'draft' WHERE status = 'pending'");
        DB::statement("UPDATE audit_reports SET status = 'completed' WHERE status = 'accepted'");
        DB::statement("UPDATE audit_reports SET status = 'draft' WHERE status = 'rejected'");

        // 3. Restore old constraint
        DB::statement("ALTER TABLE audit_reports ADD CONSTRAINT audit_reports_status_check CHECK (status IN ('draft', 'in_progress', 'completed', 'published'))");

        // 4. Restore old default
        DB::statement("ALTER TABLE audit_reports ALTER COLUMN status SET DEFAULT 'draft'");
    }
};
