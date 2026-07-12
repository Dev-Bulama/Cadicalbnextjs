@php
    $navItems = [
        ['name' => 'Dashboard', 'href' => '/admin/dashboard', 'icon' => 'layout-dashboard'],
        ['name' => 'Homepage Content', 'href' => '/admin/homepage', 'icon' => 'layout-template'],
        ['name' => 'Products', 'href' => '/admin/products', 'icon' => 'package'],
        ['name' => 'Orders', 'href' => '/admin/orders', 'icon' => 'shopping-cart'],
        ['name' => 'Bookings', 'href' => '/admin/bookings', 'icon' => 'calendar'],
        ['name' => 'Clinicians', 'href' => '/admin/clinicians', 'icon' => 'stethoscope'],
        ['name' => 'Referrals', 'href' => '/admin/referrals', 'icon' => 'git-branch'],
        ['name' => 'Institutions', 'href' => '/admin/institutions', 'icon' => 'building-2'],
        ['name' => 'Suppliers', 'icon' => 'truck', 'children' => [
            ['name' => 'All Suppliers', 'href' => '/admin/suppliers'],
            ['name' => 'RFQ Management', 'href' => '/admin/rfq'],
        ]],
        ['name' => 'Services', 'icon' => 'wrench', 'children' => [
            ['name' => 'Service Jobs', 'href' => '/admin/service-jobs'],
            ['name' => 'Technicians', 'href' => '/admin/technicians'],
            ['name' => 'Maintenance', 'href' => '/admin/maintenance'],
        ]],
        ['name' => 'Integrations', 'icon' => 'plug', 'children' => [
            ['name' => 'CRM Overview', 'href' => '/admin/integrations/crm'],
        ]],
        ['name' => 'Tracking', 'href' => '/admin/tracking', 'icon' => 'map-pin'],
        ['name' => 'Analytics', 'href' => '/admin/analytics', 'icon' => 'bar-chart-3'],
        ['name' => 'Audit Logs', 'href' => '/admin/audit-logs', 'icon' => 'shield'],
    ];
@endphp
<aside class="w-60 border-r border-slate-200 bg-white flex flex-col shrink-0">
    <div class="px-5 py-5 border-b border-slate-200">
        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-cadical-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">C</span>
            </div>
            <div>
                <p class="font-bold text-sm leading-none text-slate-900">Cadical</p>
                <p class="text-[10px] text-slate-400 leading-none mt-0.5">Admin Console</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">
        @foreach ($navItems as $item)
            @if (! isset($item['children']))
                @php $isActive = request()->is(ltrim($item['href'], '/')); @endphp
                <a href="{{ url($item['href']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $isActive ? 'bg-cadical-500 text-white shadow-sm' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-[17px] h-[17px]"></i> {{ $item['name'] }}
                </a>
            @else
                @php
                    $childActive = collect($item['children'])->contains(fn ($c) => request()->is(ltrim($c['href'], '/').'*'));
                @endphp
                <div x-data="{ open: {{ $childActive ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $childActive ? 'text-cadical-500 bg-cadical-500/5' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-[17px] h-[17px]"></i>
                        <span class="flex-1 text-left">{{ $item['name'] }}</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="ml-6 mt-1 space-y-0.5 border-l border-slate-200 pl-3">
                        @foreach ($item['children'] as $child)
                            @php $childIsActive = request()->is(ltrim($child['href'], '/').'*') || request()->is(ltrim($child['href'], '/')); @endphp
                            <a href="{{ url($child['href']) }}" class="block px-2 py-1.5 rounded-md text-xs font-medium transition-colors {{ $childIsActive ? 'text-cadical-500 bg-cadical-500/5' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                {{ $child['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>

    <div class="p-3 border-t border-slate-200">
        <form method="POST" action="{{ url('/auth/logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                <i data-lucide="log-out" class="w-4 h-4"></i> Sign out
            </button>
        </form>
    </div>
</aside>
