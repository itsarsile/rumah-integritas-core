<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        //
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('presence-chat.' . $this->message->chat_room_id),
        ];
    }

    public function broadcastWith()
    {
        // Reload the message fresh with user relationship to ensure all data is current
        // This includes file fields since they're now saved in the DB
        $fullMessage = ChatMessage::with('user')->find($this->message->id);

        // Return the array with all necessary fields (including files and user)
        return [
            'id' => $fullMessage->id,
            'chat_room_id' => $fullMessage->chat_room_id,
            'user_id' => $fullMessage->user_id,
            'message_type' => $fullMessage->message_type,
            'content' => $fullMessage->content,
            'file_name' => $fullMessage->file_name,
            'file_path' => $fullMessage->file_path,
            'file_size' => $fullMessage->file_size,
            'file_mime_type' => $fullMessage->file_mime_type,
            'created_at' => $fullMessage->created_at->toISOString(),  // Or format as needed
            'updated_at' => $fullMessage->updated_at,
            'user' => $fullMessage->user ? [
                'id' => $fullMessage->user->id,
                'name' => $fullMessage->user->name,
                // Add other user fields as needed (e.g., 'avatar' => $fullMessage->user->avatar)
            ] : null,
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
