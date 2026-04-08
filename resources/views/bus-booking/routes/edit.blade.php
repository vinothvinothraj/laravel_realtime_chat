<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Edit Route</h2></x-slot>
    <div class="py-6">
        @include('bus-booking.routes._form', ['route' => $route, 'operators' => $operators, 'action' => route('bus-booking.routes.update', $route), 'method' => 'PUT'])
    </div>
</x-app-layout>
