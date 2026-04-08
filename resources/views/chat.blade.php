<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Real-time Chat
        </h2>
    </x-slot>

    <div class="h-full min-h-0 py-6">
        <livewire:chat-room />
    </div>
</x-app-layout>
