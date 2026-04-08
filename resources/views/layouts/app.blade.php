<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-slate-100">
            <div class="flex min-h-screen">
            <aside class="w-72 border-r border-slate-200 bg-white flex flex-col">
                <div class="px-6 py-5 border-b border-slate-200 flex items-center gap-3">
                    <x-application-mark class="h-9 w-9" />
                    <div>
                        <p class="text-base font-semibold text-slate-900">{{ config('app.name', 'Laravel LiveChat') }}</p>
                        <p class="text-xs uppercase tracking-wide text-slate-400">Realtime Studio</p>
                    </div>
                </div>
                <nav class="px-4 py-6 space-y-2 text-sm">
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2 font-medium transition hover:bg-slate-100 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-600' }}"
                    >
                        Dashboard
                    </a>
                    <a
                        href="{{ route('chat') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2 font-medium transition hover:bg-slate-100 {{ request()->routeIs('chat') ? 'bg-slate-100 text-slate-900' : 'text-slate-600' }}"
                    >
                        Real-time Chat
                    </a>
                    <a
                        href="{{ route('tasks.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2 font-medium transition hover:bg-slate-100 {{ request()->routeIs('tasks.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600' }}"
                    >
                        Task Management
                    </a>
                </nav>
                <div class="mt-auto px-6 py-6 border-t border-slate-200">
                    <div class="text-xs uppercase tracking-wide text-slate-400">Signed in as</div>
                    <div class="mt-2 text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </aside>

                <div class="flex-1 flex flex-col">
                    <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm">
                        <div class="text-lg font-semibold text-slate-900">{{ $header ?? 'Workspace' }}</div>
                        <div class="flex items-center gap-3">
                            <div class="text-sm text-slate-500">{{ Auth::user()->name }}</div>
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="h-9 w-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                <span class="h-9 w-9 flex items-center justify-center rounded-full bg-slate-100 font-semibold text-slate-600">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                </span>
                            @endif
                        </div>
                    </header>

                    <main class="flex-1 overflow-auto px-4">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        @stack('scripts')
    </body>
</html>
