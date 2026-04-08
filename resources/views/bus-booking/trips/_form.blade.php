@php($current = $trip ?? null)
<form method="POST" action="{{ $action }}" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label class="block text-sm font-medium text-slate-700">Route</label>
        <select name="bus_route_id" class="mt-1 w-full rounded-lg border-slate-300" required>
            @foreach ($routes as $route)
                <option value="{{ $route->id }}" @selected(old('bus_route_id', $current->bus_route_id ?? '') == $route->id)>{{ $route->origin }} → {{ $route->destination }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Bus</label>
        <select name="bus_id" class="mt-1 w-full rounded-lg border-slate-300" required>
            @foreach ($buses as $busOption)
                <option value="{{ $busOption->id }}" @selected(old('bus_id', $current->bus_id ?? '') == $busOption->id)>{{ $busOption->name }} ({{ $busOption->registration_number }})</option>
            @endforeach
        </select>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Departure At</label>
            <input name="departure_at" type="datetime-local" value="{{ old('departure_at', isset($current->departure_at) ? $current->departure_at->format('Y-m-d\TH:i') : '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Arrival At</label>
            <input name="arrival_at" type="datetime-local" value="{{ old('arrival_at', isset($current->arrival_at) ? optional($current->arrival_at)->format('Y-m-d\TH:i') : '') }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Base Fare</label>
            <input name="base_fare" type="number" step="0.01" value="{{ old('base_fare', $current->base_fare ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border-slate-300" required>
                @foreach (['scheduled','boarding','departed','completed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $current->status?->value ?? $current->status ?? 'scheduled') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Notes</label>
        <textarea name="notes" rows="4" class="mt-1 w-full rounded-lg border-slate-300">{{ old('notes', $current->notes ?? '') }}</textarea>
    </div>
    <div class="flex justify-end gap-3">
        <a href="{{ route('bus-booking.trips.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">Cancel</a>
        <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Save</button>
    </div>
</form>
