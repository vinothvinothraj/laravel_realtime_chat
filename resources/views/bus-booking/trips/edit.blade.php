<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Edit Trip</h2></x-slot>
    <div class="py-6">
        @include('bus-booking.trips._form', ['trip' => $trip, 'routes' => $routes, 'buses' => $buses, 'action' => route('bus-booking.trips.update', $trip), 'method' => 'PUT'])
    </div>
</x-app-layout>
