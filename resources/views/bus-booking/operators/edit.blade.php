<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Edit Operator</h2></x-slot>
    <div class="py-6">
        @include('bus-booking.operators._form', ['operator' => $operator, 'action' => route('bus-booking.operators.update', $operator), 'method' => 'PUT'])
    </div>
</x-app-layout>
