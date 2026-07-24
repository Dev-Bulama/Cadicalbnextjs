<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Cadical Solutions' }}</title>
    <meta name="description" content="Cadical Solutions — medical equipment procurement, supplier marketplace, and field-service platform for the Nigerian healthcare market.">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1565C0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">

    {{-- Cart store (Alpine) — registers before Alpine boots via @livewireScripts --}}
    <script src="{{ asset('js/cart.js') }}"></script>

    {{-- Tailwind CDN (no build step) --}}
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
                        accent: {
                            DEFAULT: '#F5A623', 50: '#fef6e7', 500: '#F5A623', 600: '#d98f13',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>[x-cloak] { display: none !important; }</style>
    <style>
        [x-reveal] { opacity: 0; transform: translateY(16px); transition: opacity .5s ease, transform .5s ease; }
        [x-reveal].revealed { opacity: 1; transform: translateY(0); }
        .tabular-nums { font-variant-numeric: tabular-nums; }
    </style>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.directive('reveal', (el, { expression }) => {
                const delay = expression ? parseFloat(expression) * 1000 : 0;
                const reveal = () => el.classList.add('revealed');

                // Safety net: on some mobile browsers the IntersectionObserver callback
                // can be delayed or never fire (backgrounded tabs, slow script parsing,
                // elements taller than the viewport). Content must never stay invisible,
                // so force a reveal shortly after the element exists regardless.
                const fallback = setTimeout(() => reveal(), 1500 + delay);

                const io = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            clearTimeout(fallback);
                            setTimeout(reveal, delay);
                            io.unobserve(el);
                        }
                    });
                }, { threshold: 0, rootMargin: '0px 0px 200px 0px' });
                io.observe(el);
            });
        });

        // Global safety net: if Alpine/the reveal directive never initializes at all
        // (script load failure, slow network), don't leave the whole page blank.
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.querySelectorAll('[x-reveal]:not(.revealed)').forEach((el) => el.classList.add('revealed'));
            }, 3000);
        });
    </script>

    @livewireStyles
    {{ $head ?? '' }}
    @yield('head')
</head>
<body class="antialiased bg-white text-slate-900 font-sans">

    @include('partials.navbar')

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Toasts (mirrors `sonner` usage in the original app) --}}
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

    {{-- Lucide icons via CDN (mirrors lucide-react usage in the original app) --}}
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
    {{ $scripts ?? '' }}
    @yield('scripts')

    {{-- Admin-configured chat widget / third-party embed — deliberately unescaped, see /admin/settings → Integrations --}}
    {!! config('site.chatbot_script') !!}
</body>
</html>
