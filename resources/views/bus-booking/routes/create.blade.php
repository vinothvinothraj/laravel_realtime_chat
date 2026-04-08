<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Add Route</h2></x-slot>
    <div class="py-6">
        @include('bus-booking.routes._form', ['action' => route('bus-booking.routes.store'), 'method' => 'POST'])
    </div>
</x-app-layout>
