<nav
    x-data="{
        open: false, scrolled: false, prodOpen: false, servOpen: false,
        searchOpen: false, searchQ: '', expanded: null,
        toggleExpanded(label) { this.expanded = this.expanded === label ? null : label },
        submitSearch() { if (this.searchQ.trim()) window.location.href = '/products?search=' + encodeURIComponent(this.searchQ) }
    }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20, { passive: true })"
    :class="scrolled ? 'bg-white/98 backdrop-blur-md shadow-sm border-b border-slate-100' : 'bg-white'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 h-16 flex items-center px-4 md:px-8 justify-between"
>
    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex items-center gap-2.5 flex-shrink-0">
        <img src="{{ asset('images/logo.png') }}" alt="Cadical" class="w-8 h-8 rounded-lg">
        <div class="leading-tight hidden sm:block">
            <div class="text-cadical-500 text-sm font-bold tracking-tight">Cadical Solutions</div>
            <div class="text-[10px] text-slate-400 font-medium">Right Supply. Right Time.</div>
        </div>
    </a>

    {{-- Desktop Nav --}}
    <div class="hidden lg:flex items-center gap-1 text-sm font-medium text-slate-600">
        <div class="relative" @mouseenter="prodOpen = true" @mouseleave="prodOpen = false">
            <button class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-slate-50 hover:text-cadical-500 transition-colors">
                <i data-lucide="package" class="w-3.5 h-3.5"></i> Products
                <i data-lucide="chevron-down" class="w-3 h-3 transition-transform" :class="prodOpen && 'rotate-180'"></i>
            </button>
            <div x-show="prodOpen" x-cloak x-transition class="absolute top-full left-0 mt-1 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                <div class="px-3 py-1 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Categories</div>
                @foreach ([
                    'Diagnostics' => 'Diagnostics', 'Imaging' => 'Imaging', 'ICU' => 'ICU Equipment',
                    'Surgery' => 'Surgical', 'Laboratory' => 'Laboratory', 'Monitoring' => 'Monitoring',
                    'Dental' => 'Dental', 'Rehabilitation' => 'Rehabilitation', 'Consumables' => 'Consumables',
                ] as $slug => $label)
                    <a href="{{ url('/products') }}?category={{ $slug }}" class="block px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-cadical-500 transition-colors">{{ $label }}</a>
                @endforeach
                <div class="border-t border-slate-100 mt-1 pt-2 px-3">
                    <a href="{{ url('/products') }}" class="text-sm font-semibold text-cadical-500 hover:underline">All products &rarr;</a>
                </div>
            </div>
        </div>

        <div class="relative" @mouseenter="servOpen = true" @mouseleave="servOpen = false">
            <button class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-slate-50 hover:text-cadical-500 transition-colors">
                <i data-lucide="wrench" class="w-3.5 h-3.5"></i> Services
                <i data-lucide="chevron-down" class="w-3 h-3 transition-transform" :class="servOpen && 'rotate-180'"></i>
            </button>
            <div x-show="servOpen" x-cloak x-transition class="absolute top-full left-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                <a href="{{ url('/booking') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-cadical-500 transition-colors"><i data-lucide="wrench" class="w-3.5 h-3.5 text-cadical-500"></i> Equipment Repair</a>
                <a href="{{ url('/booking') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-cadical-500 transition-colors"><i data-lucide="bar-chart-3" class="w-3.5 h-3.5 text-cadical-500"></i> Maintenance Plans</a>
                <a href="{{ url('/institutional-portal') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-cadical-500 transition-colors"><i data-lucide="building-2" class="w-3.5 h-3.5 text-cadical-500"></i> Institutional Supply</a>
                <a href="{{ url('/dashboard/track') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-cadical-500 transition-colors"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-cadical-500"></i> Track Order</a>
            </div>
        </div>

        <a href="{{ url('/institutional-portal') }}" class="px-3 py-2 rounded-lg hover:bg-slate-50 hover:text-cadical-500 transition-colors">Institutional</a>
    </div>

    {{-- Right actions --}}
    <div class="flex items-center gap-1">
        <template x-if="searchOpen">
            <form @submit.prevent="submitSearch" class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400"></i>
                <input x-model="searchQ" x-ref="searchInput" x-init="$watch('searchOpen', v => v && $nextTick(() => $refs.searchInput.focus()))" placeholder="Search equipment..." class="bg-transparent text-sm outline-none w-36 text-slate-700">
                <button type="button" @click="searchOpen = false"><i data-lucide="x" class="w-3.5 h-3.5 text-slate-400"></i></button>
            </form>
        </template>
        <button x-show="!searchOpen" @click="searchOpen = true" class="hidden md:flex p-2 rounded-lg hover:bg-slate-50 text-slate-500 hover:text-cadical-500 transition-colors">
            <i data-lucide="search" class="w-[17px] h-[17px]"></i>
        </button>

        <a href="{{ url('/cart') }}" class="relative p-2 rounded-lg hover:bg-slate-50 text-slate-500 hover:text-cadical-500 transition-colors">
            <i data-lucide="shopping-cart" class="w-[17px] h-[17px]"></i>
            <template x-if="$store.cart.totalItems > 0">
                <span class="absolute -top-0.5 -right-0.5 bg-accent text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center" x-text="$store.cart.totalItems > 9 ? '9+' : $store.cart.totalItems"></span>
            </template>
        </a>

        @auth
            <a href="{{ url('/dashboard') }}" class="hidden md:flex p-2 rounded-lg hover:bg-slate-50 text-slate-500 hover:text-cadical-500 transition-colors"><i data-lucide="user" class="w-[17px] h-[17px]"></i></a>
        @else
            <a href="{{ url('/auth/login') }}" class="hidden md:flex p-2 rounded-lg hover:bg-slate-50 text-slate-500 hover:text-cadical-500 transition-colors"><i data-lucide="user" class="w-[17px] h-[17px]"></i></a>
        @endauth

        <a href="{{ url('/booking') }}" class="hidden lg:inline-flex items-center gap-1.5 bg-cadical-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors ml-1">Book Service</a>

        <button class="lg:hidden p-2 rounded-lg hover:bg-slate-50 text-slate-600 ml-1" @click="open = !open; expanded = null">
            <i data-lucide="x" class="w-[21px] h-[21px]" x-show="open"></i>
            <i data-lucide="menu" class="w-[21px] h-[21px]" x-show="!open"></i>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak x-transition class="absolute top-16 left-0 right-0 bg-white border-b border-slate-100 shadow-xl lg:hidden z-50 overflow-y-auto max-h-[calc(100vh-4rem)]">
        <form @submit.prevent="submitSearch" class="mx-4 mt-4 mb-2 flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5">
            <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"></i>
            <input x-model="searchQ" placeholder="Search equipment..." class="bg-transparent text-sm outline-none flex-1 text-slate-700 placeholder:text-slate-400">
        </form>

        <div class="px-4 pb-2">
            @php
                $mobileSections = [
                    'Products' => ['icon' => 'package', 'links' => [
                        ['label' => 'Diagnostics', 'href' => '/products?category=Diagnostics'],
                        ['label' => 'Imaging', 'href' => '/products?category=Imaging'],
                        ['label' => 'ICU Equipment', 'href' => '/products?category=ICU'],
                        ['label' => 'Surgical', 'href' => '/products?category=Surgery'],
                        ['label' => 'Laboratory', 'href' => '/products?category=Laboratory'],
                        ['label' => 'Monitoring', 'href' => '/products?category=Monitoring'],
                        ['label' => 'Dental', 'href' => '/products?category=Dental'],
                        ['label' => 'Rehabilitation', 'href' => '/products?category=Rehabilitation'],
                        ['label' => 'Consumables', 'href' => '/products?category=Consumables'],
                    ]],
                    'Services' => ['icon' => 'wrench', 'links' => [
                        ['label' => 'Equipment Repair', 'href' => '/booking'],
                        ['label' => 'Maintenance Plans', 'href' => '/booking'],
                        ['label' => 'Institutional Supply', 'href' => '/institutional-portal'],
                        ['label' => 'Track Order', 'href' => '/dashboard/track'],
                    ]],
                    'Company' => ['icon' => 'building-2', 'links' => [
                        ['label' => 'Institutional Portal', 'href' => '/institutional-portal'],
                        ['label' => 'About Cadical', 'href' => '/about'],
                        ['label' => 'Contact Us', 'href' => '/contact'],
                    ]],
                ];
            @endphp
            @foreach ($mobileSections as $label => $section)
                <div class="border-b border-slate-100 last:border-none">
                    <button @click="toggleExpanded('{{ $label }}')" class="w-full flex items-center justify-between py-3 text-sm font-semibold text-slate-800">
                        <span class="flex items-center gap-2"><i data-lucide="{{ $section['icon'] }}" class="w-[15px] h-[15px] text-cadical-500"></i>{{ $label }}</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="expanded === '{{ $label }}' && 'rotate-180'"></i>
                    </button>
                    <div x-show="expanded === '{{ $label }}'" x-cloak class="pb-2 pl-6 space-y-0">
                        @foreach ($section['links'] as $link)
                            <a href="{{ url($link['href']) }}" @click="open = false" class="flex items-center gap-1.5 py-2 text-sm text-slate-600 hover:text-cadical-500 transition-colors">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>{{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-4 py-4 flex flex-col gap-2.5 border-t border-slate-100">
            <a href="{{ url('/booking') }}" @click="open = false" class="bg-cadical-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold text-center">Book a Service</a>
            <a href="{{ url('/auth/login') }}" @click="open = false" class="border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-semibold text-center">Sign In</a>
        </div>
    </div>
</nav>
<div class="h-16"></div>
