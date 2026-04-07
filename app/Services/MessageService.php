<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Contracts\RoomRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MessageService
{
    public function __construct(
        protected MessageRepositoryInterface $messages,
        protected RoomRepositoryInterface $rooms
    ) {
    }

    public function recentMessages(int $roomId, int $limit = 50): Collection
    {
        return $this->messages->latestForRoom($roomId, $limit);
    }

    public function sendMessage(User $user, Room $room, string $content): Message
    {
        $message = $this->messages->create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'content' => trim($content),
        ]);

        $message->load(['user', 'room']);

        Log::debug('Chat message sent', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'message_id' => $message->id,
            'content' => $message->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }

    public function roomsForUser(User $user): Collection
    {
        return $this->rooms->forUser($user->id);
    }

    public function roomWith(User $currentUser, int $otherUserId): Room
    {
        return $this->rooms->findOrCreateDirectRoom($currentUser->id, $otherUserId);
    }

    public function createGroup(string $name, User $owner, array $participants): Room
    {
        return $this->rooms->createGroup($name, $owner->id, $participants);
    }
}
