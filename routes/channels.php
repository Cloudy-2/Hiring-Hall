<?php

use App\Models\Chats\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversations.{conversation}', function ($user, Conversation $conversation) {
    // Moderators can access any conversation as silent member
    // or they cant be seen lols
    if ($user->isModerator()) {
        return true;
    }

    return $conversation->participants()->where('user_id', $user->id)->exists();
});

Broadcast::channel('users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Presence channel for online status - returns user info for presence tracking
Broadcast::channel('chat.presence', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->profile_photo_url,
    ];
});

// Monitor channel for admins/moderators to receive real-time updates
Broadcast::channel('chat.monitor', function ($user) {
    // Only allow moderators/admins/super_admins to subscribe
    return in_array($user->role, ['moderator', 'admin', 'super_admin']);
});
