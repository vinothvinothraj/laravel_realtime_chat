@php($current = $route ?? null)
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
            <label class="block text-sm font-medium text-slate-700">Code</label>
            <input name="code" value="{{ old('code', $current->code ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Duration minutes</label>
            <input name="duration_minutes" type="number" value="{{ old('duration_minutes', $current->duration_minutes ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Origin</label>
            <input name="origin" value="{{ old('origin', $current->origin ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Destination</label>
            <input name="destination" value="{{ old('destination', $current->destination ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
        </div>
    </div>
    <label class="flex items-center gap-2 text-sm text-slate-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $current->is_active ?? true))>
        Active
    </label>
    <div class="flex justify-end gap-3">
        <a href="{{ route('bus-booking.routes.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">Cancel</a>
        <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Save</button>
    </div>
</form>
