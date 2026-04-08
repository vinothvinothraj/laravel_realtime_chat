<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Bookings</h2></x-slot>
    <div class="py-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Reference</th>
                        <th class="px-4 py-3">Passenger</th>
                        <th class="px-4 py-3">Trip</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($bookings as $booking)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $booking->booking_reference }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $booking->contact_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $booking->trip?->route?->origin }} → {{ $booking->trip?->route?->destination }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $booking->status->value ?? $booking->status }}</td>
                            <td class="px-4 py-3">
                                <a class="text-slate-700" href="{{ route('bus-booking.bookings.show', $booking) }}">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</x-app-layout>
