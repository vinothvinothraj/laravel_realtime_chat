<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\ManagesEcho;
use App\Models\Room;
use App\Services\MessageService;
use Livewire\Component;

class ChatRoom extends Component
{
    public array $messages = [];

    public string $message = '';

    public int $currentUserId;

    public ?int $activeRoomId = null;

    public array $rooms = [];

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
    }

    public function render()
    {
        return view('livewire.chat-room', [
            'rooms' => $this->rooms,
        ]);
    }

    protected function refreshRooms(?int $preferredRoomId = null): void
    {
        $roomModels = $this->messageService()->roomsForUser(auth()->user());
        $this->rooms = $roomModels->map(fn (Room $room) => [
            'id' => $room->id,
            'name' => $room->name ?? $this->roomName($room),
            'is_group' => $room->is_group,
        ])->toArray();

        if ($preferredRoomId) {
            $this->activeRoomId = $preferredRoomId;
        }

        if (!$this->activeRoomId && count($this->rooms)) {
            $this->activeRoomId = $this->rooms[0]['id'];
        }

        $this->loadMessages();

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

    protected function messageService(): MessageService
    {
        return app(MessageService::class);
    }
}
