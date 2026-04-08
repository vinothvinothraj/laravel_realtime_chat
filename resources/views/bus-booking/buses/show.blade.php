<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Bus Details</h2></x-slot>
    <div class="py-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-lg font-semibold text-slate-900">{{ $bus->name }}</div>
            <div class="mt-2 text-sm text-slate-600">Registration: {{ $bus->registration_number }}</div>
            <div class="mt-2 text-sm text-slate-600">Type: {{ $bus->bus_type }}</div>
            <div class="mt-2 text-sm text-slate-600">Operator: {{ $bus->operator?->name }}</div>
            <div class="mt-2 text-sm text-slate-600">Seats: {{ $bus->seats->count() }}</div>
            <div class="mt-2 text-sm text-slate-600">Trips: {{ $bus->trips->count() }}</div>
        </div>
    </div>
</x-app-layout>
