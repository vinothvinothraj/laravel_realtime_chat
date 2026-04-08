<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">Buses</h2>
            <a href="{{ route('bus-booking.buses.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Add Bus</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Registration</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Operator</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($buses as $bus)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $bus->registration_number }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $bus->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $bus->bus_type }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $bus->operator?->name }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a class="text-slate-700" href="{{ route('bus-booking.buses.show', $bus) }}">View</a>
                                    <a class="text-slate-700" href="{{ route('bus-booking.buses.edit', $bus) }}">Edit</a>
                                    <form method="POST" action="{{ route('bus-booking.buses.destroy', $bus) }}" onsubmit="return confirm('Delete this bus?')">
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
        <div class="mt-4">{{ $buses->links() }}</div>
    </div>
</x-app-layout>
