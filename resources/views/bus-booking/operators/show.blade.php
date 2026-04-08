<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Operator Details</h2></x-slot>
    <div class="py-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-lg font-semibold text-slate-900">{{ $operator->name }}</div>
            <div class="mt-2 text-sm text-slate-600">Slug: {{ $operator->slug }}</div>
            <div class="mt-2 text-sm text-slate-600">Phone: {{ $operator->contact_phone ?? '-' }}</div>
            <div class="mt-2 text-sm text-slate-600">Email: {{ $operator->contact_email ?? '-' }}</div>
            <div class="mt-2 text-sm text-slate-600">Routes: {{ $operator->routes_count ?? $operator->routes()->count() }}</div>
            <div class="mt-2 text-sm text-slate-600">Buses: {{ $operator->buses_count ?? $operator->buses()->count() }}</div>
        </div>
    </div>
</x-app-layout>
