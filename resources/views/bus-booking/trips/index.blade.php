<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">Trips</h2>
            <a href="{{ route('bus-booking.trips.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Add Trip</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Route</th>
                        <th class="px-4 py-3">Bus</th>
                        <th class="px-4 py-3">Departure</th>
                        <th class="px-4 py-3">Fare</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($trips as $trip)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $trip->route?->origin }} → {{ $trip->route?->destination }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $trip->bus?->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $trip->departure_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ number_format($trip->base_fare, 2) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a class="text-slate-700" href="{{ route('bus-booking.trips.show', $trip) }}">View</a>
                                    <a class="text-slate-700" href="{{ route('bus-booking.trips.edit', $trip) }}">Edit</a>
                                    <form method="POST" action="{{ route('bus-booking.trips.destroy', $trip) }}" onsubmit="return confirm('Delete this trip?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $trips->links() }}</div>
    </div>
</x-app-layout>
