<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat-room.{room}', function ($user) {
    return (bool) $user;
});
