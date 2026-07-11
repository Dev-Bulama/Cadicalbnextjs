<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Cadical Tech' }}</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1565C0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">

    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cadical: {
                            50: '#e8f1fb', 100: '#c7ddf5', 200: '#9cc4ee', 300: '#6aa7e4',
                            400: '#3d8bd9', 500: '#1565C0', 600: '#0f57ab', 700: '#0d47a1',
                            800: '#0a3a83', 900: '#082e69',
                        },
                        accent: { DEFAULT: '#F5A623', 50: '#fef6e7', 500: '#F5A623', 600: '#d98f13' },
                    },
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                },
            },
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>[x-cloak] { display: none !important; }</style>

    @livewireStyles
    @yield('head')
</head>
<body class="antialiased bg-slate-50 text-slate-900 font-sans">

    <div class="flex flex-col min-h-screen">
        <header class="bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-cadical-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xs">C</span>
                </div>
                <span class="font-bold text-sm">Cadical Tech</span>
            </div>
            <form method="POST" action="{{ url('/auth/logout') }}">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-full bg-cadical-500/10 flex items-center justify-center text-cadical-500">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </header>

        <main class="flex-1 pb-20 max-w-lg w-full mx-auto">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        @php
            $techNav = [
                ['name' => 'Jobs', 'href' => '/technician/jobs', 'icon' => 'briefcase'],
                ['name' => 'Schedule', 'href' => '/technician/schedule', 'icon' => 'calendar'],
                ['name' => 'Tracking', 'href' => '/technician/tracking', 'icon' => 'map-pin'],
                ['name' => 'Alerts', 'href' => '/technician/notifications', 'icon' => 'bell'],
                ['name' => 'Profile', 'href' => '/technician/profile', 'icon' => 'user'],
            ];
        @endphp
        <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-200">
            <div class="grid grid-cols-5 max-w-lg mx-auto">
                @foreach ($techNav as $item)
                    @php $isActive = request()->is(ltrim($item['href'], '/').'*'); @endphp
                    <a href="{{ url($item['href']) }}" class="flex flex-col items-center gap-0.5 py-2 px-1 transition-colors {{ $isActive ? 'text-cadical-500' : 'text-slate-400 hover:text-slate-700' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                        <span class="text-[10px] font-medium">{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>
    </div>

    <div
        x-data="{ toasts: [] }"
        @cart-toast.window="const id = Date.now(); toasts.push({ id, message: $event.detail.message }); $nextTick(() => window.lucide && window.lucide.createIcons()); setTimeout(() => toasts = toasts.filter(t => t.id !== id), 3000)"
        class="fixed bottom-24 right-4 z-[100] flex flex-col gap-2 items-end"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" x-transition class="bg-slate-900 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-lg flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        function renderIcons() { if (window.lucide) window.lucide.createIcons() }
        document.addEventListener('DOMContentLoaded', renderIcons);
        document.addEventListener('livewire:navigated', renderIcons);
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', renderIcons);
            Livewire.hook('commit', ({ succeed }) => succeed(() => renderIcons()));
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(() => {});
            });
        }
    </script>

    @livewireScripts
    @yield('scripts')
</body>
</html>
