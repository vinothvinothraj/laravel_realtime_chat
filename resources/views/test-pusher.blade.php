<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Pusher Smoke Test
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-4">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-2xl border border-gray-100 p-6 space-y-3">
            <p class="text-sm text-slate-600">
                When you press the button below PHP will trigger a hard-coded payload directly through
                Pusher on the <code>test-pusher</code> channel. Every authenticated browser tab
                listening to that channel should display the incoming payload instantly.
            </p>

            <form method="POST" action="{{ route('test-pusher.trigger') }}">
                @csrf

                <div class="flex justify-center">
                    <x-button>
                        Push "Hello world" to all listeners
                    </x-button>
                </div>
            </form>
        </div>

        <div class="bg-slate-900/5 border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-600 space-y-2">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-slate-800">Live Payloads</span>
                <span class="text-xs text-slate-500">
                    Open this tab in multiple sessions to confirm each one receives the same text.
                </span>
            </div>
            <div id="test-pusher-feed" class="space-y-2">
                <div class="text-slate-400 text-xs">
                    Awaiting incoming payloads...
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (!window.pusher) {
                    console.warn('Pusher is not ready yet for test-pusher');
                    return;
                }

                const channel = window.pusher.subscribe('test-pusher');

                channel.bind('my-event', (data) => {
                    const feed = document.getElementById('test-pusher-feed');
                    const row = document.createElement('div');
                    row.className = 'rounded-xl bg-white px-4 py-3 shadow-sm text-slate-700 border border-slate-200';
                    row.textContent = `${data.message ?? 'Hello'} (from ${data.sender?.name ?? 'System'})`;
                    feed.prepend(row);
                });
            });
        </script>
    @endpush
</x-app-layout>
