<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\ManagesEcho;
use App\Models\Message;
use App\Models\Room;
use App\Services\MessageService;
use Livewire\Component;
use Illuminate\Support\Str;

class ChatRoom extends Component
{
    public array $messages = [];

    public string $message = '';

    public int $currentUserId;

    public ?int $activeRoomId = null;

    public array $rooms = [];

    public string $conversationFilter = 'chats';

    protected ?int $lastSubscribedRoomId = null;

    public string $groupName = '';

    public array $groupParticipants = [];

    protected $listeners = [
        'incomingMessage',
    ];

    protected array $rules = [
        'message' => 'required|string|max:1000',
        'groupName' => 'nullable|string|max:100',
    ];

    public function mount(): void
    {
        $this->currentUserId = auth()->id();
        $this->refreshRooms();
    }

    public function setConversationFilter(string $filter): void
    {
        if (! in_array($filter, ['chats', 'groups'], true)) {
            return;
        }

        $this->conversationFilter = $filter;
    }

    public function startDirectChat(int $userId): void
    {
        $room = $this->messageService()->roomWith(auth()->user(), $userId);
        $this->refreshRooms($room->id);
    }

    public function createGroup(): void
    {
        $this->validateOnly('groupName');

        if (empty($this->groupParticipants)) {
            return;
        }

        $room = $this->messageService()->createGroup(trim($this->groupName), auth()->user(), $this->groupParticipants);
        $this->groupName = '';
        $this->groupParticipants = [];
        $this->refreshRooms($room->id);
    }

    public function selectRoom(int $roomId): void
    {
        $this->refreshRooms($roomId);
    }

    public function incomingMessage(array $message): void
    {
        if ((int) ($message['room_id'] ?? 0) === $this->activeRoomId) {
            $this->messages[] = $message;
            $this->markRoomAsRead((int) $message['room_id']);
            $this->refreshRooms($this->activeRoomId);
        }
    }

    public function sendMessage(): void
    {
        $this->validate();

        if (!$this->activeRoomId) {
            return;
        }

        $room = Room::find($this->activeRoomId);

        if (!$room) {
            return;
        }

        $newMessage = $this->messageService()->sendMessage(
            auth()->user(),
            $room,
            $this->message,
        );

        $this->messages[] = $newMessage->toArray();
        $this->message = '';
        $this->refreshRooms($this->activeRoomId);
    }

    public function render()
    {
        $rooms = collect($this->rooms);

        return view('livewire.chat-room', [
            'rooms' => $this->rooms,
            'chatRooms' => $rooms->where('is_group', false)->values()->all(),
            'groupRooms' => $rooms->where('is_group', true)->values()->all(),
        ]);
    }

    protected function refreshRooms(?int $preferredRoomId = null): void
    {
        $roomModels = $this->messageService()->roomsForUser(auth()->user());

        if ($preferredRoomId) {
            $this->activeRoomId = $preferredRoomId;
        }

        if (!$this->activeRoomId && $roomModels->isNotEmpty()) {
            $this->activeRoomId = $roomModels->first()->id;
        }

        $this->rooms = $roomModels->map(function (Room $room) {
            $latestMessage = $room->latestMessage;
            $lastSeenId = (int) (session('chat.read_state.' . $room->id) ?? 0);
            $unreadCount = $this->roomUnreadCount($room, $lastSeenId);

            if ($this->activeRoomId === $room->id) {
                $unreadCount = 0;
            }

            return [
                'id' => $room->id,
                'name' => $room->name ?? $this->roomName($room),
                'is_group' => $room->is_group,
                'members' => $room->participants->count(),
                'subtitle' => $room->is_group
                    ? ($room->participants->count() . ' members')
                    : $this->roomSubtitle($room),
                'avatar' => $this->roomAvatar($room),
                'preview' => $this->roomPreview($room),
                'timestamp' => $latestMessage?->created_at?->format('h:i A'),
                'unread_count' => $unreadCount,
            ];
        })->toArray();

        $this->loadMessages();
        $this->markRoomAsRead($this->activeRoomId);

        if ($this->activeRoomId && $this->activeRoomId !== $this->lastSubscribedRoomId) {
            $this->lastSubscribedRoomId = $this->activeRoomId;
        }
    }

    protected function loadMessages(): void
    {
        if ($this->activeRoomId) {
            $this->messages = $this->messageService()->recentMessages($this->activeRoomId)->toArray();
        } else {
            $this->messages = [];
        }
    }

    protected function roomName(Room $room): string
    {
        $names = $room->participants
            ->where('id', '!=', $this->currentUserId)
            ->pluck('name')
            ->toArray();

        return $room->is_group ? ($room->name ?? implode(', ', $names)) : ($names[0] ?? 'Chat');
    }

    protected function roomSubtitle(Room $room): string
    {
        $otherUser = $room->participants
            ->where('id', '!=', $this->currentUserId)
            ->first();

        if ($room->is_group) {
            return trim(($room->participants->count() ?: 0) . ' members');
        }

        return $otherUser ? 'Direct chat' : 'No other participant';
    }

    protected function roomAvatar(Room $room): string
    {
        if ($room->is_group) {
            return strtoupper(substr($room->name ?? 'G', 0, 1));
        }

        $name = $room->participants
            ->where('id', '!=', $this->currentUserId)
            ->first()?->name ?? 'Chat';

        return strtoupper(substr($name, 0, 1));
    }

    protected function roomPreview(Room $room): string
    {
        $latestMessage = $room->latestMessage;

        if (! $latestMessage) {
            return $room->is_group ? 'No messages yet' : 'Start the conversation';
        }

        $prefix = $latestMessage->user_id === $this->currentUserId
            ? 'You: '
            : (($latestMessage->user?->name ?? 'Someone') . ': ');

        return Str::limit($prefix . $latestMessage->content, 34);
    }

    protected function roomUnreadCount(Room $room, int $lastSeenId): int
    {
        if ($lastSeenId <= 0) {
            $lastSeenId = 0;
        }

        return Message::query()
            ->where('room_id', $room->id)
            ->where('id', '>', $lastSeenId)
            ->where('user_id', '!=', $this->currentUserId)
            ->count();
    }

    protected function markRoomAsRead(?int $roomId): void
    {
        if (! $roomId) {
            return;
        }

        $latestMessageId = Room::query()
            ->with('latestMessage')
            ->find($roomId)
            ?->latestMessage?->id;

        if (! $latestMessageId) {
            return;
        }

        $readState = session('chat.read_state', []);
        $readState[$roomId] = $latestMessageId;
        session(['chat.read_state' => $readState]);
    }

    protected function messageService(): MessageService
    {
        return app(MessageService::class);
    }
}
