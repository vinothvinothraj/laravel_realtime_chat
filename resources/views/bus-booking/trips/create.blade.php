<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Add Trip</h2></x-slot>
    <div class="py-6">
        @include('bus-booking.trips._form', ['action' => route('bus-booking.trips.store'), 'method' => 'POST'])
    </div>
</x-app-layout>
