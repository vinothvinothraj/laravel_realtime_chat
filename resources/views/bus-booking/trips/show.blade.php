<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Trip Details</h2></x-slot>
    <div class="py-6 space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-lg font-semibold text-slate-900">{{ $trip->route?->origin }} → {{ $trip->route?->destination }}</div>
            <div class="mt-2 text-sm text-slate-600">Bus: {{ $trip->bus?->name }}</div>
            <div class="mt-2 text-sm text-slate-600">Departure: {{ $trip->departure_at?->format('Y-m-d H:i') }}</div>
            <div class="mt-2 text-sm text-slate-600">Arrival: {{ $trip->arrival_at?->format('Y-m-d H:i') ?? '-' }}</div>
            <div class="mt-2 text-sm text-slate-600">Fare: {{ number_format($trip->base_fare, 2) }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-base font-semibold text-slate-900">Seat Inventory</div>
            <div class="mt-4 grid grid-cols-4 gap-3 md:grid-cols-6 lg:grid-cols-8">
                @foreach ($trip->seats as $seat)
                    <div class="rounded-xl border border-slate-200 px-3 py-2 text-center text-xs text-slate-700">
                        {{ $seat->seat?->seat_number ?? 'Seat' }}
                        <div class="mt-1 text-[11px] text-slate-400">{{ $seat->status->value ?? $seat->status }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
