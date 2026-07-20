@php $user = auth()->user(); @endphp
<header class="border-b border-slate-200 bg-white px-4 lg:px-8 py-4 flex justify-between items-center gap-3">
    <div class="flex items-center gap-3 min-w-0">
        <button @click="mobileNav = true" class="lg:hidden w-9 h-9 shrink-0 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        <div class="min-w-0">
            <h2 class="text-lg font-semibold text-slate-900 truncate">{{ $title ?? 'Welcome back!' }}</h2>
            <p class="text-sm text-slate-400 truncate">{{ $user->email }}</p>
        </div>
    </div>

    <div class="flex items-center gap-4 shrink-0">
        <a href="{{ url('/') }}" target="_blank" class="hidden sm:inline text-sm text-slate-400 hover:text-cadical-500 transition-colors">View site &rarr;</a>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 shrink-0 rounded-full bg-cadical-500 text-white flex items-center justify-center font-semibold text-sm">
                {{ strtoupper(substr($user->email, 0, 1)) }}
            </div>
            <div class="text-sm hidden sm:block">
                <p class="font-medium text-slate-900">{{ $user->name ?: 'Admin' }}</p>
                <p class="text-slate-400 text-xs">{{ $user->role === \App\Models\User::ROLE_SUPER_ADMIN ? 'Super Admin' : 'Admin' }}</p>
            </div>
        </div>
    </div>
</header>
