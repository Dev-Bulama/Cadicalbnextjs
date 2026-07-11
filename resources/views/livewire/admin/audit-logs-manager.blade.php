<div>
    <div class="flex gap-3 mb-6 flex-wrap">
        <div class="relative flex-1 max-w-sm">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by email or entity ID…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
        <select wire:model.live="actionFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All actions</option>
            @foreach ($actions as $a)
                <option value="{{ $a }}">{{ ucfirst($a) }}</option>
            @endforeach
        </select>
        <select wire:model.live="entityFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All entities</option>
            @foreach ($entities as $e)
                <option value="{{ $e }}">{{ ucfirst($e) }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                    <th class="px-4 py-3 font-medium">Timestamp</th>
                    <th class="px-4 py-3 font-medium">User</th>
                    <th class="px-4 py-3 font-medium">Action</th>
                    <th class="px-4 py-3 font-medium">Entity</th>
                    <th class="px-4 py-3 font-medium">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr wire:key="log-{{ $log->id }}" class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="px-4 py-3 text-slate-500">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td class="px-4 py-3">
                            <p class="text-slate-700">{{ $log->user_email ?? 'System' }}</p>
                            <p class="text-xs text-slate-400">{{ $log->user_role }}</p>
                        </td>
                        <td class="px-4 py-3"><x-admin.badge color="cadical">{{ $log->action }}</x-admin.badge></td>
                        <td class="px-4 py-3 text-slate-600">{{ $log->entity }} @if($log->entity_id) #{{ $log->entity_id }} @endif</td>
                        <td class="px-4 py-3 text-slate-500">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-slate-400">No audit logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
</div>
