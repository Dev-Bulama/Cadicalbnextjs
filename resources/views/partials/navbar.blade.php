@php $siteLogo = \App\Models\HomeSection::mediaUrl(config('site.logo')) ?: asset('images/logo.png'); @endphp
<nav
    x-data="{
        open: false, scrolled: false, prodOpen: false, servOpen: false, acctOpen: false,
        searchOpen: false, searchQ: '', expanded: null,
        toggleExpanded(label) { this.expanded = this.expanded === label ? null : label },
        submitSearch() { if (this.searchQ.trim()) window.location.href = '/products?search=' + encodeURIComponent(this.searchQ) }
    }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20, { passive: true })"
    x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''"
    :class="scrolled ? 'bg-white/98 backdrop-blur-md shadow-sm border-b border-slate-100' : 'bg-white'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 h-16 flex items-center px-4 md:px-8 justify-between"
>
    {{-- Logo --}}
    <a href="{{ url('/') }}" class="flex items-center gap-2.5 flex-shrink-0">
        <img src="{{ $siteLogo }}" alt="Cadical" class="w-8 h-8 rounded-lg object-contain">
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
                <a href="{{ url('/track') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-cadical-500 transition-colors"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-cadical-500"></i> Track Order</a>
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
            <div class="relative hidden md:block" @mouseenter="acctOpen = true" @mouseleave="acctOpen = false">
                <button class="flex p-2 rounded-lg hover:bg-slate-50 text-slate-500 hover:text-cadical-500 transition-colors">
                    <i data-lucide="user" class="w-[17px] h-[17px]"></i>
                </button>
                <div x-show="acctOpen" x-cloak x-transition class="absolute top-full right-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                    <div class="px-3 py-1.5 border-b border-slate-100 mb-1">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-cadical-500 transition-colors"><i data-lucide="layout-dashboard" class="w-3.5 h-3.5 text-cadical-500"></i> My Account</a>
                    <a href="{{ url('/notifications') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-cadical-500 transition-colors"><i data-lucide="bell" class="w-3.5 h-3.5 text-cadical-500"></i> Notifications</a>
                    <form method="POST" action="{{ url('/auth/logout') }}" class="border-t border-slate-100 mt-1 pt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"><i data-lucide="log-out" class="w-3.5 h-3.5"></i> Sign Out</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ url('/auth/login') }}" class="hidden md:flex p-2 rounded-lg hover:bg-slate-50 text-slate-500 hover:text-cadical-500 transition-colors"><i data-lucide="user" class="w-[17px] h-[17px]"></i></a>
        @endauth

        <a href="{{ url('/booking') }}" class="hidden lg:inline-flex items-center gap-1.5 bg-cadical-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-cadical-700 transition-colors ml-1">Book Service</a>

        <button class="lg:hidden p-2 rounded-lg hover:bg-slate-50 text-slate-600 ml-1" @click="open = !open; expanded = null">
            <i data-lucide="menu" class="w-[21px] h-[21px]"></i>
        </button>
    </div>

    {{-- Mobile menu: full-screen slide-in drawer from the right --}}
    <div x-show="open" x-cloak class="lg:hidden">
        <div x-show="open" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-slate-900/50 z-40"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed top-0 right-0 bottom-0 w-[88%] max-w-sm bg-white z-50 shadow-2xl flex flex-col">
            <div class="flex items-center justify-between px-4 h-16 border-b border-slate-100 flex-shrink-0">
                <span class="font-bold text-sm text-slate-800">Menu</span>
                <button @click="open = false" class="p-2 rounded-lg hover:bg-slate-50 text-slate-600">
                    <i data-lucide="x" class="w-[21px] h-[21px]"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto">
                <form @submit.prevent="submitSearch" class="mx-4 mt-4 mb-2 flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"></i>
                    <input x-model="searchQ" placeholder="Search equipment..." class="bg-transparent text-sm outline-none flex-1 text-slate-700 placeholder:text-slate-400">
                </form>

                @auth
                    <div class="mx-4 mt-2 mb-1 flex items-center gap-3 bg-blue-50/60 rounded-xl px-3 py-3">
                        <div class="w-9 h-9 rounded-full bg-cadical-500/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="user" class="w-4 h-4 text-cadical-500"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                @endauth

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
                                ['label' => 'Track Order', 'href' => '/track'],
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

                    @auth
                        <a href="{{ url('/dashboard') }}" @click="open = false" class="flex items-center gap-2 py-3 text-sm font-semibold text-slate-800 border-b border-slate-100">
                            <i data-lucide="layout-dashboard" class="w-[15px] h-[15px] text-cadical-500"></i> My Account
                        </a>
                        <a href="{{ url('/notifications') }}" @click="open = false" class="flex items-center gap-2 py-3 text-sm font-semibold text-slate-800">
                            <i data-lucide="bell" class="w-[15px] h-[15px] text-cadical-500"></i> Notifications
                        </a>
                    @endauth
                </div>
            </div>

            <div class="px-4 py-4 flex flex-col gap-2.5 border-t border-slate-100 flex-shrink-0">
                <a href="{{ url('/booking') }}" @click="open = false" class="bg-cadical-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold text-center">Book a Service</a>
                @auth
                    <form method="POST" action="{{ url('/auth/logout') }}">
                        @csrf
                        <button type="submit" class="w-full border border-red-200 text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold text-center">Sign Out</button>
                    </form>
                @else
                    <a href="{{ url('/auth/login') }}" @click="open = false" class="border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-sm font-semibold text-center">Sign In</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
<div class="h-16"></div>
