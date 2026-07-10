<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} — Cadical Solutions</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

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

    <div class="flex h-screen overflow-hidden" x-data="{ mobileNav: false }">
        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0">
            @include('admin.partials.header')
            <main class="flex-1 overflow-y-auto">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    <div
        x-data="{ toasts: [] }"
        @cart-toast.window="const id = Date.now(); toasts.push({ id, message: $event.detail.message }); $nextTick(() => window.lucide && window.lucide.createIcons()); setTimeout(() => toasts = toasts.filter(t => t.id !== id), 3000)"
        class="fixed bottom-4 right-4 z-[100] flex flex-col gap-2 items-end"
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

    @livewireScripts
    @yield('scripts')
</body>
</html>
