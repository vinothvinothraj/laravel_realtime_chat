@php($current = $bus ?? null)
<form method="POST" action="{{ $action }}" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label class="block text-sm font-medium text-slate-700">Operator</label>
        <select name="bus_operator_id" class="mt-1 w-full rounded-lg border-slate-300" required>
            @foreach ($operators as $operator)
                <option value="{{ $operator->id }}" @selected(old('bus_operator_id', $current->bus_operator_id ?? '') == $operator->id)>{{ $operator->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Name</label>
            <input name="name" value="{{ old('name', $current->name ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Registration Number</label>
            <input name="registration_number" value="{{ old('registration_number', $current->registration_number ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Bus Type</label>
            <input name="bus_type" value="{{ old('bus_type', $current->bus_type ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Seat Capacity</label>
            <input name="seat_capacity" type="number" value="{{ old('seat_capacity', $current->seat_capacity ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Seat Layout JSON</label>
        <textarea name="seat_layout" rows="4" class="mt-1 w-full rounded-lg border-slate-300">{{ old('seat_layout', isset($current->seat_layout) ? json_encode($current->seat_layout) : '') }}</textarea>
    </div>
    <label class="flex items-center gap-2 text-sm text-slate-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $current->is_active ?? true))>
        Active
    </label>
    <div class="flex justify-end gap-3">
        <a href="{{ route('bus-booking.buses.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">Cancel</a>
        <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Save</button>
    </div>
</form>
