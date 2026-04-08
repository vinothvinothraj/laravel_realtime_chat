<x-app-layout>
    @php
        $defaultStartDate = \Illuminate\Support\Carbon::now()->subDays(30)->toDateString();
        $defaultEndDate = \Illuminate\Support\Carbon::today()->toDateString();
    @endphp

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-slate-800">
                    Task Management
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Keep work visible, filter by user, and move tasks across the board.
                </p>
            </div>
        </div>
    </x-slot>

    <div
        data-task-board
        data-move-url-template="{{ route('tasks.move', ['task' => '__TASK__']) }}"
        data-today="{{ $defaultEndDate }}"
        x-data="{ showTaskModal: false, modalStatus: 'todo', modalTitle: 'Create task' }"
        x-init="if ({{ $errors->any() ? 'true' : 'false' }}) showTaskModal = true"
        x-on:task-modal-open.window="showTaskModal = true; modalStatus = $event.detail.status ?? 'todo'; modalTitle = $event.detail.title ?? 'Create task'"
        class="space-y-6 py-6"
    >
        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div class="grid flex-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
                    <div class="space-y-2">
                        <label for="user-filter" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <i class="fa-solid fa-users text-slate-500"></i>
                            Assignee
                        </label>
                        <select
                            id="user-filter"
                            data-user-filter
                            class="w-full rounded-xl border-slate-300 bg-white text-slate-700 shadow-sm focus:border-slate-400 focus:ring-slate-400"
                        >
                            <option value="all">All users</option>
                            @foreach ($users as $userOption)
                                <option value="{{ $userOption['id'] }}">{{ $userOption['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="start-date" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <i class="fa-regular fa-calendar-days text-slate-500"></i>
                            Start date
                        </label>
                        <input
                            type="date"
                            id="start-date"
                            data-start-date
                            value="{{ $defaultStartDate }}"
                            max="{{ $defaultEndDate }}"
                            class="w-full rounded-xl border-slate-300 bg-white text-slate-700 shadow-sm focus:border-slate-400 focus:ring-slate-400"
                            aria-label="Start date"
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="end-date" class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <i class="fa-regular fa-calendar-check text-slate-500"></i>
                            End date
                        </label>
                        <input
                            type="date"
                            id="end-date"
                            data-end-date
                            value="{{ $defaultEndDate }}"
                            max="{{ $defaultEndDate }}"
                            class="w-full rounded-xl border-slate-300 bg-white text-slate-700 shadow-sm focus:border-slate-400 focus:ring-slate-400"
                            aria-label="End date"
                        />
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            data-clear-filter
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 md:w-auto"
                        >
                            <i class="fa-solid fa-rotate-right text-slate-500"></i>
                            Clear filter
                        </button>
                    </div>
                </div>

            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-4">
            @foreach ($columns as $column)
                @php
                    $accent = match ($column['value']) {
                        'todo' => 'border-sky-100 bg-sky-50',
                        'in_progress' => 'border-amber-100 bg-amber-50',
                        'testing' => 'border-violet-100 bg-violet-50',
                        'completed' => 'border-emerald-100 bg-emerald-50',
                    };

                    $dot = match ($column['value']) {
                        'todo' => 'bg-sky-400',
                        'in_progress' => 'bg-amber-400',
                        'testing' => 'bg-violet-400',
                        'completed' => 'bg-emerald-400',
                    };

                    $columnIcon = match ($column['value']) {
                        'todo' => 'fa-clipboard-list',
                        'in_progress' => 'fa-spinner',
                        'testing' => 'fa-flask-vial',
                        'completed' => 'fa-circle-check',
                    };
                @endphp

                <article class="rounded-[1.75rem] border border-slate-200 bg-white p-3 shadow-sm" data-task-column="{{ $column['value'] }}">
                    <header class="flex items-center justify-between gap-3 rounded-[1.35rem] {{ $accent }} px-4 py-3">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid {{ $columnIcon }} text-slate-500"></i>
                            <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">{{ $column['label'] }}</h3>
                                <p class="text-xs text-slate-500">{{ $column['description'] }}</p>
                            </div>
                        </div>
                        <span class="inline-flex min-w-8 items-center justify-center rounded-full bg-white px-2 py-1 text-xs font-semibold text-slate-700 shadow-sm" data-task-count="{{ $column['value'] }}">
                            {{ $column['count'] }}
                        </span>
                    </header>

                    <div class="mt-3 min-h-[24rem] space-y-3 rounded-[1.35rem] border border-dashed border-slate-200 bg-slate-50 p-3" data-task-list data-task-status="{{ $column['value'] }}">
                        <div
                            class="rounded-2xl border border-dashed border-slate-200 bg-white px-4 py-6 text-center text-sm text-slate-500"
                            data-task-empty
                            @if ($column['count'] > 0) hidden @endif
                        >
                            No tasks to show here.
                        </div>

                        @foreach ($column['tasks'] as $task)
                            <div
                                class="overflow-hidden rounded-[1.35rem] border border-slate-200 border-l-4 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md {{ $column['value'] === 'todo' ? 'border-l-sky-400' : ($column['value'] === 'in_progress' ? 'border-l-amber-400' : ($column['value'] === 'testing' ? 'border-l-violet-400' : 'border-l-emerald-400')) }}"
                                draggable="true"
                                data-task-card
                                data-task-id="{{ $task->id }}"
                                data-task-user-id="{{ $task->user_id }}"
                                data-task-created-at="{{ $task->created_at?->toIso8601String() }}"
                            >
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h4 class="truncate text-sm font-semibold text-slate-900">{{ $task->title }}</h4>
                                            @if ($task->description)
                                                <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-500">
                                                    {{ $task->description }}
                                                </p>
                                            @endif
                                        </div>
                                        <i class="fa-regular fa-clipboard text-slate-300"></i>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between gap-3 text-[11px] text-slate-400">
                                        <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 font-medium text-slate-600">
                                            <i class="fa-solid fa-user text-slate-500"></i>
                                            {{ $task->user?->name ?? 'Unknown user' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-slate-500">
                                            <i class="fa-regular fa-clock"></i>
                                            {{ $task->created_at?->format('M d, H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button
                            type="button"
                            x-on:click="$dispatch('task-modal-open', { status: '{{ $column['value'] }}', title: 'New task in {{ $column['label'] }}' })"
                            class="flex w-full items-center justify-center gap-2 rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-slate-400 hover:bg-slate-50"
                        >
                            <i class="fa-solid fa-plus text-slate-500"></i>
                            Add new
                        </button>
                    </div>
                </article>
            @endforeach
        </section>

        <div
            x-cloak
            x-show="showTaskModal"
            x-on:keydown.escape.window="showTaskModal = false"
            class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
            style="display: none;"
        >
            <div
                x-show="showTaskModal"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/25"
                x-on:click="showTaskModal = false"
            ></div>

            <div
                data-task-modal-panel
                x-show="showTaskModal"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-3 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-3 scale-95"
                class="task-modal-panel relative mx-auto w-full max-w-lg overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl"
                x-trap.inert.noscroll="showTaskModal"
            >
                <div data-task-modal-drag-handle class="flex cursor-move items-start justify-between gap-4 border-b border-slate-200 bg-slate-50 px-6 py-5">
                    <div>
                        <h3 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
                            <i class="fa-solid fa-list-check text-slate-500"></i>
                            <span x-text="modalTitle"></span>
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">Drag the header to move this panel, or resize it from the corner.</p>
                    </div>
                    <button
                        type="button"
                        x-on:click="showTaskModal = false"
                        class="rounded-full p-2 text-slate-400 transition hover:bg-white hover:text-slate-700"
                        aria-label="Close"
                    >
                        <span class="text-lg leading-none">&times;</span>
                    </button>
                </div>

                <form method="POST" action="{{ route('tasks.store') }}" class="space-y-5 px-6 py-6">
                    @csrf

                    <div>
                        <x-label for="title" value="Task title" />
                        <x-input
                            id="title"
                            name="title"
                            type="text"
                            class="mt-2 block w-full"
                            placeholder="Write the task title"
                            value="{{ old('title') }}"
                            required
                        />
                        <x-input-error for="title" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="description" value="Description" />
                        <textarea
                            id="description"
                            name="description"
                            rows="2"
                            class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-400 focus:ring-slate-400"
                            placeholder="Add context, links, or acceptance notes"
                        >{{ old('description') }}</textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="status" value="Column" />
                        <select
                            id="status"
                            name="status"
                            class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-400 focus:ring-slate-400"
                            x-model="modalStatus"
                        >
                            @foreach ($statusOptions as $option)
                                <option value="{{ $option['value'] }}">
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="status" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-4">
                        <x-secondary-button type="button" x-on:click="showTaskModal = false">
                            Cancel
                        </x-secondary-button>
                        <x-button class="bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">
                            Save task
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
