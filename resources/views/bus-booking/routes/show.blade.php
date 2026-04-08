<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Route Details</h2></x-slot>
    <div class="py-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-lg font-semibold text-slate-900">{{ $route->code }}</div>
            <div class="mt-2 text-sm text-slate-600">{{ $route->origin }} → {{ $route->destination }}</div>
            <div class="mt-2 text-sm text-slate-600">Operator: {{ $route->operator?->name }}</div>
            <div class="mt-2 text-sm text-slate-600">Duration: {{ $route->duration_minutes }} minutes</div>
            <div class="mt-2 text-sm text-slate-600">Trips: {{ $route->trips()->count() }}</div>
        </div>
    </div>
</x-app-layout>
