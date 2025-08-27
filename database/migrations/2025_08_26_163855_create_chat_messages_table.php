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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained('chat_rooms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('message_type', ['text', 'file', 'system', 'action'])->default('text');
            $table->text('content')->nullable();
            
            // File fields
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('file_mime_type', 100)->nullable();
            
            // Message metadata
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->foreignId('reply_to_message_id')->nullable()->constrained('chat_messages');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['user_id', 'message_type']);
        });

        DB::statement('CREATE INDEX chat_messages_content_fts ON chat_messages USING GIN(to_tsvector(\'indonesian\', content)) WHERE message_type = \'text\' AND content IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
