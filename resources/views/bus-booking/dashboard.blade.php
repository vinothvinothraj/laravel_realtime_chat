<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-bus text-slate-500"></i>
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                Bus Booking Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="h-full min-h-0 py-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            @foreach ($summary as $label => $value)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="text-xs uppercase tracking-wide text-slate-400">{{ ucfirst($label) }}</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('bus-booking.operators.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300">
                <div class="text-sm font-semibold text-slate-900">Operators</div>
                <div class="mt-1 text-sm text-slate-500">Manage transport companies</div>
            </a>
            <a href="{{ route('bus-booking.routes.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300">
                <div class="text-sm font-semibold text-slate-900">Routes</div>
                <div class="mt-1 text-sm text-slate-500">Create origin and destination flows</div>
            </a>
            <a href="{{ route('bus-booking.buses.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300">
                <div class="text-sm font-semibold text-slate-900">Buses</div>
                <div class="mt-1 text-sm text-slate-500">Manage vehicles and seat layouts</div>
            </a>
            <a href="{{ route('bus-booking.trips.index') }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300">
                <div class="text-sm font-semibold text-slate-900">Trips</div>
                <div class="mt-1 text-sm text-slate-500">Schedule departures and fares</div>
            </a>
        </div>
    </div>
</x-app-layout>
