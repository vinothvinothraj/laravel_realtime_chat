<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Booking Details</h2></x-slot>
    <div class="py-6 space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-lg font-semibold text-slate-900">{{ $booking->booking_reference }}</div>
            <div class="mt-2 text-sm text-slate-600">Contact: {{ $booking->contact_name }} | {{ $booking->contact_phone }}</div>
            <div class="mt-2 text-sm text-slate-600">Trip: {{ $booking->trip?->route?->origin }} → {{ $booking->trip?->route?->destination }}</div>
            <div class="mt-2 text-sm text-slate-600">Status: {{ $booking->status->value ?? $booking->status }}</div>
            <div class="mt-2 text-sm text-slate-600">Payment: {{ $booking->payment_status->value ?? $booking->payment_status }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-base font-semibold text-slate-900">Passengers</div>
            <div class="mt-4 space-y-2">
                @foreach ($booking->passengers as $passenger)
                    <div class="rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700">
                        {{ $passenger->full_name }} @if($passenger->seat) - {{ $passenger->seat->seat_number }} @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
