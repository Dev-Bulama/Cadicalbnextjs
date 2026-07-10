@php
    $statusColor = fn ($s) => match ($s) { 'confirmed' => 'emerald', 'cancelled' => 'red', 'completed' => 'blue', default => 'slate' };
@endphp
<div>
    <div class="flex items-center justify-between mb-6">
        <div class="relative w-72">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or ref…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-400 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">Ref</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Service</th>
                    <th class="px-4 py-3 text-left">Phone</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($bookings as $b)
                    <tr wire:key="booking-{{ $b->id }}" wire:click="select({{ $b->id }})" class="cursor-pointer hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $b->ref }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $b->first_name }} {{ $b->last_name }}</td>
                        <td class="px-4 py-3 text-slate-500 capitalize">{{ $b->service }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $b->phone }}</td>
                        <td class="px-4 py-3"><x-admin.badge :color="$statusColor($b->status)">{{ $b->status }}</x-admin.badge></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-400">No bookings found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $bookings->links() }}</div>

    @if ($this->selected)
        <div class="fixed inset-0 bg-black/40 flex justify-end z-50" wire:click.self="closeDrawer">
            <div class="w-full max-w-md bg-white h-full p-6 space-y-4 shadow-xl overflow-y-auto">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-slate-900">Booking Details</h2>
                    <button wire:click="closeDrawer" class="text-slate-400 hover:text-slate-700">✕</button>
                </div>

                <div class="text-sm space-y-2 text-slate-700">
                    <p><strong>Ref:</strong> {{ $this->selected->ref }}</p>
                    <p><strong>Name:</strong> {{ $this->selected->first_name }} {{ $this->selected->last_name }}</p>
                    <p><strong>Service:</strong> {{ $this->selected->service }}</p>
                    <p><strong>Phone:</strong> {{ $this->selected->phone }}</p>
                    <p><strong>Email:</strong> {{ $this->selected->email }}</p>
                    <p><strong>Location:</strong> {{ $this->selected->location }}</p>
                    <p><strong>Notes:</strong> {{ $this->selected->notes ?: 'None' }}</p>
                </div>

                <div class="flex gap-2 pt-4">
                    <button wire:click="updateStatus({{ $this->selected->id }}, 'confirmed')" class="flex-1 bg-cadical-500 hover:bg-cadical-700 text-white py-2 rounded-lg text-sm font-semibold transition-colors">Confirm</button>
                    <button wire:click="updateStatus({{ $this->selected->id }}, 'completed')" class="flex-1 border border-slate-200 text-slate-700 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-colors">Complete</button>
                    <button wire:click="updateStatus({{ $this->selected->id }}, 'cancelled')" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm font-semibold transition-colors">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
