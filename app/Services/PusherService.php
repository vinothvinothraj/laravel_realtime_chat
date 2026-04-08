<?php

namespace App\Services;

use App\Models\Message;
use Pusher\Pusher;

class PusherService
{
    protected Pusher $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('services.pusher.key'),
            config('services.pusher.secret'),
            config('services.pusher.app_id'),
            [
                'cluster' => config('services.pusher.cluster'),
                'useTLS' => true,
            ]
        );
    }

    public function trigger(string $channel, string $event, array $payload): void
    {
        $this->pusher->trigger($channel, $event, $payload);
    }

    public function triggerChatMessage(Message $message): void
    {
        $payload = $this->messagePayload($message);
        $channel = 'chat-room.'.$message->room_id;

        $this->trigger($channel, 'send-message', $payload);
        $this->trigger($channel, 'received-message', $payload);
    }

    public function messagePayload(Message $message): array
    {
        $user = $message->user;
        $room = $message->room;

        return [
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'room_id' => $message->room_id,
                'room_name' => $room?->name,
                'created_at' => $message->created_at?->toDateTimeString(),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_photo' => $user->profile_photo_url,
                ],
            ],
        ];
    }
}
