<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code', 50)->unique();
            $table->string('report_title');
            $table->text('report_description');
            $table->string('lhp_number', 100)->nullable()->unique();
            $table->foreignId('regional_government_organization_id')
                ->constrained('regional_government_organizations', 'id')
                ->cascadeOnDelete();

            $table->date('audit_start_date')->nullable();
            $table->date('audit_end_date')->nullable();
            $table->enum('audit_type', ['performance', 'financial', 'compliance', 'investigative'])->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed', 'published'])->default('draft');

            $table->string('lhp_document_path')->nullable();
            $table->string('lhp_document_name')->nullable();
            $table->string('lhp_document_mime_type')->nullable();
            $table->bigInteger('lhp_document_size')->nullable(); 

            $table->json('audit_scope')->nullable();
            $table->json('findings_summary')->nullable(); 
            $table->decimal('total_findings_amount', 15, 2)->nullable();
            $table->integer('findings_count')->default(0);

            $table->string('lead_auditor')->nullable();
            $table->json('audit_team')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->cascadeOnDelete();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['regional_government_organization_id', 'status']);
            $table->index('audit_start_date');
            $table->index('lhp_number');
            $table->fullText(['report_title', 'report_description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_reports');
    }
};
