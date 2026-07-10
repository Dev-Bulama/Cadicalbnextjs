@php $user = auth()->user(); @endphp
<header class="border-b border-slate-200 bg-white px-8 py-4 flex justify-between items-center">
    <div>
        <h2 class="text-lg font-semibold text-slate-900">{{ $title ?? 'Welcome back!' }}</h2>
        <p class="text-sm text-slate-400">{{ $user->email }}</p>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ url('/') }}" target="_blank" class="text-sm text-slate-400 hover:text-cadical-500 transition-colors">View site &rarr;</a>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-cadical-500 text-white flex items-center justify-center font-semibold text-sm">
                {{ strtoupper(substr($user->email, 0, 1)) }}
            </div>
            <div class="text-sm">
                <p class="font-medium text-slate-900">{{ $user->name ?: 'Admin' }}</p>
                <p class="text-slate-400 text-xs">{{ $user->role === \App\Models\User::ROLE_SUPER_ADMIN ? 'Super Admin' : 'Admin' }}</p>
            </div>
        </div>
    </div>
</header>
