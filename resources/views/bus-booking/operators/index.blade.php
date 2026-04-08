<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">Operators</h2>
            <a href="{{ route('bus-booking.operators.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Add Operator</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($operators as $operator)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $operator->name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $operator->slug }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $operator->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a class="text-slate-700" href="{{ route('bus-booking.operators.show', $operator) }}">View</a>
                                    <a class="text-slate-700" href="{{ route('bus-booking.operators.edit', $operator) }}">Edit</a>
                                    <form method="POST" action="{{ route('bus-booking.operators.destroy', $operator) }}" onsubmit="return confirm('Delete this operator?')">
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
        <div class="mt-4">{{ $operators->links() }}</div>
    </div>
</x-app-layout>
