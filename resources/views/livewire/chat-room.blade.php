<div id="chat-room-root" data-current-user-id="{{ auth()->id() }}" class="w-full">
    <div class="w-full rounded-xl overflow-hidden shadow-2xl border border-slate-200 bg-white">
        <div class="flex min-h-[85vh] items-stretch">
            <aside class="hidden lg:flex lg:w-1/5 flex-col border-r border-slate-200 bg-slate-50 self-stretch h-full">
                <div class="px-5 pt-4 pb-3 border-b border-slate-200 bg-slate-50/80 backdrop-blur">
                    <div class="mt-3 grid grid-cols-2 rounded-2xl bg-slate-100 p-1 text-sm">
                        <button
                            wire:click="setConversationFilter('chats')"
                            class="rounded-xl px-3 py-2 transition {{ $conversationFilter === 'chats' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' }}"
                        >
                            Chats <span class="ml-1 text-[10px] text-slate-400">({{ count($chatRooms) }})</span>
                        </button>
                        <button
                            wire:click="setConversationFilter('groups')"
                            class="rounded-xl px-3 py-2 transition {{ $conversationFilter === 'groups' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' }}"
                        >
                            Groups <span class="ml-1 text-[10px] text-slate-400">({{ count($groupRooms) }})</span>
                        </button>
                    </div>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto px-3 py-3">
                    <div class="space-y-2">
                        @if ($conversationFilter === 'chats')
                            @forelse ($chatRooms as $room)
                                <button
                                    wire:click="selectRoom({{ $room['id'] }})"
                                    class="w-full text-left rounded-2xl px-3 py-3 border transition flex items-start gap-3 {{ $activeRoomId === $room['id'] ? 'border-emerald-500 bg-emerald-50 text-slate-900 shadow-sm shadow-emerald-100' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50' }}"
                                >
                                    <span class="h-11 w-11 rounded-full bg-gradient-to-br from-slate-800 via-slate-700 to-slate-900 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ $room['avatar'] }}
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="flex items-start justify-between gap-2">
                                            <span class="min-w-0">
                                                <span class="block text-sm font-semibold truncate">{{ $room['name'] }}</span>
                                                <span class="mt-0.5 block text-[11px] text-slate-400 truncate">{{ $room['preview'] }}</span>
                                            </span>
                                            <span class="flex flex-col items-end gap-2 shrink-0">
                                                @if (! empty($room['timestamp']))
                                                    <span class="text-[10px] uppercase tracking-[0.2em] text-slate-400">{{ $room['timestamp'] }}</span>
                                                @endif
                                                @if (! empty($room['unread_count']))
                                                    <span class="inline-flex min-w-5 h-5 items-center justify-center rounded-full bg-emerald-600 px-1.5 text-[10px] font-semibold text-white">
                                                        {{ $room['unread_count'] > 9 ? '9+' : $room['unread_count'] }}
                                                    </span>
                                                @else
                                                    <span class="h-2 w-2 rounded-full {{ $activeRoomId === $room['id'] ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                                @endif
                                            </span>
                                        </span>
                                    </span>
                                </button>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-4 py-4 text-xs text-slate-500">
                                    No direct chats yet.
                                </div>
                            @endforelse
                        @else
                            @forelse ($groupRooms as $room)
                                <button
                                    wire:click="selectRoom({{ $room['id'] }})"
                                    class="w-full text-left rounded-2xl px-3 py-3 border transition flex items-start gap-3 {{ $activeRoomId === $room['id'] ? 'border-emerald-500 bg-emerald-50 text-slate-900 shadow-sm shadow-emerald-100' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50' }}"
                                >
                                    <span class="h-11 w-11 rounded-full bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                        {{ $room['avatar'] }}
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="flex items-start justify-between gap-2">
                                            <span class="min-w-0">
                                                <span class="block text-sm font-semibold truncate">{{ $room['name'] }}</span>
                                                <span class="mt-0.5 block text-[11px] text-slate-400 truncate">{{ $room['preview'] }}</span>
                                            </span>
                                            <span class="flex flex-col items-end gap-2 shrink-0">
                                                @if (! empty($room['timestamp']))
                                                    <span class="text-[10px] uppercase tracking-[0.2em] text-slate-400">{{ $room['timestamp'] }}</span>
                                                @endif
                                                @if (! empty($room['unread_count']))
                                                    <span class="inline-flex min-w-5 h-5 items-center justify-center rounded-full bg-emerald-600 px-1.5 text-[10px] font-semibold text-white">
                                                        {{ $room['unread_count'] > 9 ? '9+' : $room['unread_count'] }}
                                                    </span>
                                                @else
                                                    <span class="text-[10px] uppercase tracking-[0.25em] text-slate-400">group</span>
                                                @endif
                                            </span>
                                        </span>
                                    </span>
                                </button>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-4 py-4 text-xs text-slate-500">
                                    No group conversations yet.
                                </div>
                            @endforelse
                        @endif
                    </div>

                    @if ($conversationFilter === 'groups')
                        @if ($participants->isNotEmpty())
                            <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="text-xs uppercase tracking-[0.35em] text-slate-400">Create group</div>
                                <input
                                    wire:model.defer="groupName"
                                    type="text"
                                    placeholder="Group name"
                                    class="mt-3 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100"
                                />
                                <div class="mt-3 space-y-2 max-h-36 overflow-y-auto">
                                    @foreach ($participants as $participant)
                                        <label class="flex items-center gap-2 text-sm rounded-lg px-2 py-1 hover:bg-slate-50">
                                            <input type="checkbox" wire:model="groupParticipants" value="{{ $participant->id }}" class="form-checkbox h-4 w-4 text-emerald-500" />
                                            <span class="text-slate-600">{{ $participant->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <x-button wire:click="createGroup" class="mt-4 w-full text-xs py-2 bg-emerald-600 hover:bg-emerald-700">
                                    Create group
                                </x-button>
                            </div>
                        @endif
                    @endif
                </div>
            </aside>

            <section class="w-full lg:w-4/5 flex flex-col min-h-0">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <div class="mt-1 text-2xl font-bold text-slate-900">Conversations</div>
                </div>

                <div
                    id="chat-window"
                    class="flex-1 min-h-0 px-3 py-4 space-y-1.5 overflow-y-auto bg-[url('data:image/svg+xml,%3Csvg width=\"32\" height=\"32\" viewBox=\"0 0 32 32\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"%23e2e8f0\" fill-opacity=\"0.15\" fill-rule=\"evenodd\"%3E%3Cpath d=\"M6 6h8v8H6z\"/%3E%3Cpath d=\"M18 6h8v8h-8z\"/%3E%3Cpath d=\"M6 18h8v8H6z\"/%3E%3Cpath d=\"M18 18h8v8h-8z\"/%3E%3C/g%3E%3C/svg%3E')]">
                    @php $currentUserId = auth()->id(); @endphp

                    @forelse ($messages as $message)
                        @php
                            $isSelf = ($message['user']['id'] ?? null) === $currentUserId;
                        @endphp

                        <div class="flex {{ $isSelf ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-[40%] rounded-2xl px-3 py-2 text-sm leading-snug {{ $isSelf ? 'bg-emerald-100 text-slate-900 rounded-br-none shadow-sm shadow-emerald-200' : 'bg-white text-slate-700 rounded-bl-none shadow-sm shadow-slate-200' }}"
                            >
                                <div class="text-[11px] font-semibold tracking-wide text-slate-400 mb-0.5">
                                    {{ $isSelf ? 'You' : ($message['user']['name'] ?? 'Unknown') }}
                                </div>
                                <div class="break-words whitespace-pre-line">
                                    {{ $message['content'] }}
                                </div>
                                <div class="text-[10px] mt-0.5 text-slate-400 {{ $isSelf ? 'text-right' : '' }}">
                                    {{ \Illuminate\Support\Carbon::parse($message['created_at'] ?? now())->format('h:i A') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-sm text-slate-500">
                            No messages yet - send a hello to start the thread.
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
                <div id="active-room-trigger" data-room="{{ $activeRoomId }}" class="hidden"></div>
            </section>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const chatWindow = document.getElementById('chat-window');
                const chatRoot = document.getElementById('chat-room-root');
                const activeRoomTrigger = document.getElementById('active-room-trigger');
                const currentUserId = parseInt(chatRoot?.dataset.currentUserId || 0, 10);
                let activeChannel = null;
                let activeChannelName = null;

                const subscribeRoom = (roomId) => {
                    if (!window.pusher || !roomId) {
                        return;
                    }

                    const nextChannelName = `chat-room.${roomId}`;

                    if (activeChannelName === nextChannelName) {
                        return;
                    }

                    if (activeChannelName) {
                        activeChannel?.unbind_all();
                        window.pusher.unsubscribe(activeChannelName);
                    }

                    activeChannelName = nextChannelName;
                    activeChannel = window.pusher.subscribe(activeChannelName);
                    console.log('live chat channel subscribed', activeChannelName);

                    activeChannel.bind('send-message', (data) => {
                        console.debug('live chat send event', data);
                    });

                    activeChannel.bind('received-message', (data) => {
                        const message = data?.message;

                        if (!message || message.user?.id === currentUserId) {
                            return;
                        }

                        console.log('live chat incoming event', data);
                        Livewire.dispatch('incomingMessage', { message });
                    });
                };

                const triggerId = () => parseInt(activeRoomTrigger?.dataset.room || 0, 10);
                subscribeRoom(triggerId());

                if (activeRoomTrigger) {
                    const roomObserver = new MutationObserver(() => {
                        subscribeRoom(triggerId());
                    });

                    roomObserver.observe(activeRoomTrigger, {
                        attributes: true,
                        attributeFilter: ['data-room'],
                    });
                }

                if (chatWindow) {
                    const scrollObserver = new MutationObserver(() => {
                        chatWindow.scrollTop = chatWindow.scrollHeight;
                    });

                    scrollObserver.observe(chatWindow, {
                        childList: true,
                        subtree: true,
                    });
                }
            });
        </script>
    @endpush
</div>
