<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Add Operator</h2></x-slot>
    <div class="py-6">
        @include('bus-booking.operators._form', ['action' => route('bus-booking.operators.store'), 'method' => 'POST'])
    </div>
</x-app-layout>
