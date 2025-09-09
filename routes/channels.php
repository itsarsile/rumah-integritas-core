<?php

use App\Models\ChatRoomParticipant;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatRoomId}', function($user, $chatRoomId) {
    return ChatRoomParticipant::where('chat_room_id', $chatRoomId)->where('user_id', $user->id)->whereNull('left_at')->exists();
});

Broadcast::channel('presence-chat.{chatRoomId}', function ($user, $chatRoomId) {
    $participant = ChatRoomParticipant::where('chat_room_id', $chatRoomId)
        ->where('user_id', $user->id)
        ->whereNull('left_at')
        ->first();

    if ($participant) {
        return [
            'id' => $user->id,
            'name' => $user->name, 
            'role' => $participant->role,
        ];
    }

    return false; 
});