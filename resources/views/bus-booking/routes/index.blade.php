<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">Routes</h2>
            <a href="{{ route('bus-booking.routes.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Add Route</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Code</th>
                        <th class="px-4 py-3">Route</th>
                        <th class="px-4 py-3">Operator</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($routes as $route)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $route->code }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $route->origin }} → {{ $route->destination }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $route->operator?->name }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a class="text-slate-700" href="{{ route('bus-booking.routes.show', $route) }}">View</a>
                                    <a class="text-slate-700" href="{{ route('bus-booking.routes.edit', $route) }}">Edit</a>
                                    <form method="POST" action="{{ route('bus-booking.routes.destroy', $route) }}" onsubmit="return confirm('Delete this route?')">
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
        <div class="mt-4">{{ $routes->links() }}</div>
    </div>
</x-app-layout>
