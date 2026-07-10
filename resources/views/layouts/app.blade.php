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

    @livewireStyles
    {{ $head ?? '' }}
</head>
<body class="antialiased bg-white text-slate-900 font-sans">

    @include('partials.navbar')

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Lucide icons via CDN (mirrors lucide-react usage in the original app) --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        function renderIcons() { if (window.lucide) window.lucide.createIcons() }
        document.addEventListener('DOMContentLoaded', renderIcons);
        document.addEventListener('livewire:navigated', renderIcons);
        document.addEventListener('livewire:update', renderIcons);
    </script>

    @livewireScripts
    {{ $scripts ?? '' }}
</body>
</html>
