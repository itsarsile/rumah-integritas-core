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
        Schema::create('chat_room_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained('chat_rooms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('role', ['admin', 'moderator', 'member', 'observer'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('last_seen_at')->nullable();
            $table->foreignId('last_read_message_id')->nullable()->constrained('chat_messages');
            $table->boolean('is_muted')->default(false);
            $table->timestamp('left_at')->nullable();
            $table->timestamps();
            
            $table->unique(['chat_room_id', 'user_id']);
            $table->index(['chat_room_id', 'left_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_room_participants');
    }
};
