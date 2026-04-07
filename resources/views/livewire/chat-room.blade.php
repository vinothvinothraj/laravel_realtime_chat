<div class="w-full">
    <div class="w-full rounded-3xl overflow-hidden shadow-2xl border border-slate-200 bg-white">
        <div class="flex min-h-[80vh]">
            <div class="w-1/3 hidden lg:flex flex-col border-r border-slate-200 bg-slate-50">
                <div class="px-5 py-4">
                    <div class="text-xs uppercase tracking-[0.4em] text-slate-400">Participants</div>
                    <div class="text-lg font-semibold text-slate-800 mt-2">Active people</div>
                    <p class="text-[12px] text-slate-500 mt-1">Only other logged-in users are listed here.</p>
                </div>
                <div class="px-5">
                    <div class="text-xs uppercase text-slate-400">Conversations</div>
                    <div class="mt-3 space-y-2">
                        @foreach ($rooms as $room)
                            <button
                                wire:click="selectRoom({{ $room['id'] }})"
                                class="w-full text-left rounded-xl px-3 py-2 border {{ $activeRoomId === $room['id'] ? 'border-emerald-500 bg-emerald-50 text-slate-900' : 'border-slate-200 bg-white text-slate-600' }}"
                            >
                                <div class="text-sm font-semibold">{{ $room['name'] }}</div>
                                <div class="text-[11px] uppercase tracking-widest text-slate-400">
                                    {{ $room['is_group'] ? 'Group' : 'Direct' }}
                                </div>
                            </button>
                        @endforeach
                        @if (empty($rooms))
                            <div class="text-xs text-slate-500">You have no conversations yet.</div>
                        @endif
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto px-3 pb-4">
                    @forelse ($participants as $participant)
                        <div class="flex items-center justify-between rounded-xl px-3 py-2 mb-2 border border-slate-100 bg-white shadow-sm">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $participant->name }}</div>
                                <div class="text-xs text-emerald-500 uppercase tracking-widest">online</div>
                            </div>
                            <span class="h-8 w-8 rounded-full bg-gradient-to-br from-slate-700 via-slate-900 to-slate-800 text-white flex items-center justify-center text-xs font-semibold">
                                {{ strtoupper(substr($participant->name, 0, 1)) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-xs text-slate-500 px-3 py-2">
                            No other participants available.
                        </div>
                    @endforelse
                    @if ($participants->isNotEmpty())
                        <div class="mt-4 border-t border-slate-200 pt-3">
                            <label class="text-xs uppercase tracking-widest text-slate-400">Create group</label>
                            <input
                                wire:model.defer="groupName"
                                type="text"
                                placeholder="Group name"
                                class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100"
                            />
                            <div class="mt-2 space-y-1 max-h-32 overflow-y-auto">
                                @foreach ($participants as $participant)
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="checkbox" wire:model="groupParticipants" value="{{ $participant->id }}" class="form-checkbox h-4 w-4 text-emerald-500" />
                                        <span class="text-slate-600">{{ $participant->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-button wire:click="createGroup" class="mt-3 text-xs py-1 px-3 bg-emerald-600 hover:bg-emerald-700">
                                Create group
                            </x-button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-full lg:w-2/3 flex flex-col">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                <div class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Active chat</div>
                <div class="mt-1 text-2xl font-bold text-slate-900">Realtime Conversations</div>
                <p class="text-xs text-slate-400">Every message sent is instantly pushed via Pusher</p>
            </div>

            <div
                id="chat-window"
                class="flex-1 px-4 py-6 space-y-2 overflow-y-auto bg-[url('data:image/svg+xml,%3Csvg width=\"32\" height=\"32\" viewBox=\"0 0 32 32\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"%23e2e8f0\" fill-opacity=\"0.15\" fill-rule=\"evenodd\"%3E%3Cpath d=\"M6 6h8v8H6z\"/%3E%3Cpath d=\"M18 6h8v8h-8z\"/%3E%3Cpath d=\"M6 18h8v8H6z\"/%3E%3Cpath d=\"M18 18h8v8h-8z\"/%3E%3C/g%3E%3C/svg%3E')]">
                @php $currentUserId = auth()->id(); @endphp

                @forelse ($messages as $message)
                    @php
                        $isSelf = ($message['user']['id'] ?? null) === $currentUserId;
                    @endphp

                    <div class="flex {{ $isSelf ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-[70%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed {{ $isSelf ? 'bg-emerald-100 text-slate-900 rounded-br-none shadow-sm shadow-emerald-200' : 'bg-white text-slate-700 rounded-bl-none shadow-sm shadow-slate-200' }}"
                        >
                            <div class="text-xs font-semibold tracking-wide text-slate-400 mb-1">
                                {{ $isSelf ? 'You' : ($message['user']['name'] ?? 'Unknown') }}
                            </div>
                            <div class="break-words whitespace-pre-line">
                                {{ $message['content'] }}
                            </div>
                            <div class="text-[11px] mt-2 text-slate-400 {{ $isSelf ? 'text-right' : '' }}">
                                {{ \Illuminate\Support\Carbon::parse($message['created_at'] ?? now())->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-sm text-slate-500">
                        No messages yet—send a hello to start the thread.
                    </div>
                @endforelse
            </div>

            <form wire:submit.prevent="sendMessage" class="px-5 pb-6 pt-4 bg-slate-50 border-t border-slate-200">
                <div class="flex gap-3 items-end">
                    <textarea
                        id="message"
                        wire:model.defer="message"
                        rows="2"
                        class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition"
                        placeholder="Type a message..."
                    ></textarea>
                    <div class="flex flex-col gap-2">
                        <x-input-error for="message" class="text-xs text-red-500" />
                        <x-button class="bg-emerald-600 hover:bg-emerald-700 px-5 py-2 text-sm">
                            Send
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="active-room-trigger" data-room="{{ $activeRoomId }}" class="hidden"></div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', () => {
                const chatWindow = document.getElementById('chat-window');
                const activeRoomTrigger = document.getElementById('active-room-trigger');
                let activeChannel = null;

                const subscribeRoom = (roomId) => {
                    if (!window.Echo || !roomId) {
                        return;
                    }

                    if (activeChannel) {
                        activeChannel.stopListening();
                    }

                    activeChannel = window.Echo.channel(`chat-room.${roomId}`);
                    console.log('live chat channel subscribed', activeChannel.name);

                    activeChannel.listen('MessageSent', (event) => {
                        console.log('live chat incoming event', event);
                        Livewire.emit('incomingMessage', event.message);
                    });
                };

                const triggerId = () => parseInt(activeRoomTrigger?.dataset.room || 0, 10);
                subscribeRoom(triggerId());

                Livewire.hook('element.updated', (el) => {
                    if (!activeRoomTrigger || el.id !== 'active-room-trigger') {
                        return;
                    }

                    subscribeRoom(triggerId());
                });

                Livewire.hook('message.processed', () => {
                    if (chatWindow) {
                        chatWindow.scrollTop = chatWindow.scrollHeight;
                    }
                });
            });
        </script>
    @endpush
</div>
