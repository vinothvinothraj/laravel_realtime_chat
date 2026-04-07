<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Message $message,
    ) {
    }

    public function broadcastOn(): Channel
    {
        return new Channel('chat-room.'.$this->message->room_id);
    }

    public function broadcastWith(): array
    {
        $user = $this->message->user;

        $room = $this->message->room;

        return [
            'message' => [
                'id' => $this->message->id,
                'content' => $this->message->content,
                'room_id' => $this->message->room_id,
                'room_name' => $room?->name,
                'created_at' => $this->message->created_at?->toDateTimeString(),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo' => $user->profile_photo_url,
                ],
            ],
        ];
    }
}
