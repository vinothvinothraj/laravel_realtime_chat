<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Edit Bus</h2></x-slot>
    <div class="py-6">
        @include('bus-booking.buses._form', ['bus' => $bus, 'operators' => $operators, 'action' => route('bus-booking.buses.update', $bus), 'method' => 'PUT'])
    </div>
</x-app-layout>
