@php($current = $operator ?? null)
<form method="POST" action="{{ $action }}" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label class="block text-sm font-medium text-slate-700">Name</label>
        <input name="name" value="{{ old('name', $current->name ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Slug</label>
        <input name="slug" value="{{ old('slug', $current->slug ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300" required>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">Phone</label>
            <input name="contact_phone" value="{{ old('contact_phone', $current->contact_phone ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Email</label>
            <input name="contact_email" type="email" value="{{ old('contact_email', $current->contact_email ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
    </div>
    <label class="flex items-center gap-2 text-sm text-slate-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $current->is_active ?? true))>
        Active
    </label>
    <div class="flex justify-end gap-3">
        <a href="{{ route('bus-booking.operators.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">Cancel</a>
        <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Save</button>
    </div>
</form>
