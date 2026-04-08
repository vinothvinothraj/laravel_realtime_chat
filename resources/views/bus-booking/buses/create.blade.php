<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Add Bus</h2></x-slot>
    <div class="py-6">
        @include('bus-booking.buses._form', ['action' => route('bus-booking.buses.store'), 'method' => 'POST'])
    </div>
</x-app-layout>
