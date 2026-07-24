@php
    $statusColors = ['PENDING' => 'amber', 'PAID' => 'cadical', 'PROCESSING' => 'cadical', 'SHIPPED' => 'amber', 'DELIVERED' => 'emerald', 'CANCELLED' => 'red'];
@endphp
<div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card icon="shopping-cart" label="Total Orders" value="{{ $stats['total'] }}" color="cadical" />
        <x-admin.stat-card icon="clock" label="Pending" value="{{ $stats['pending'] }}" color="amber" />
        <x-admin.stat-card icon="loader" label="Processing" value="{{ $stats['processing'] }}" color="cadical" />
        <x-admin.stat-card icon="check-circle" label="Delivered" value="{{ $stats['delivered'] }}" color="emerald" />
    </div>

    <div class="flex gap-3 mb-6">
        <div class="relative flex-1 max-w-sm">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by tracking code or email…" class="w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cadical-500/20 focus:border-cadical-500">
        </div>
        <select wire:model.live="statusFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All statuses</option>
            @foreach (['PENDING', 'PAID', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED'] as $s)
                <option value="{{ $s }}">{{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                    <th class="px-4 py-3 font-medium">Tracking Code</th>
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="px-4 py-3 font-medium">Amount</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Date</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr wire:key="order-{{ $order->id }}" class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $order->tracking_code }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            <div class="font-medium text-slate-900">{{ $order->customerName() }}</div>
                            <div class="text-xs text-slate-400">{{ $order->customerEmail() ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">₦{{ number_format($order->total_amount, 0) }}</td>
                        <td class="px-4 py-3"><x-admin.badge :color="$statusColors[$order->status] ?? 'slate'">{{ $order->status }}</x-admin.badge></td>
                        <td class="px-4 py-3 text-slate-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="open({{ $order->id }})" class="h-7 px-2.5 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">View</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-slate-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>

    @if ($active)
        <div class="fixed inset-0 z-50 flex justify-end" x-data @keydown.escape.window="$wire.closeDrawer()">
            <div class="absolute inset-0 bg-slate-900/40" wire:click="closeDrawer"></div>
            <div class="relative w-full max-w-md bg-white h-full overflow-y-auto shadow-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-slate-900">{{ $active->tracking_code }}</h3>
                    <button wire:click="closeDrawer" class="text-slate-400 hover:text-slate-700"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>

                <div class="space-y-1.5 mb-4">
                    @foreach (['PENDING', 'PAID', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED'] as $s)
                        <button wire:click="updateStatus({{ $active->id }}, '{{ $s }}')" class="w-full text-left px-3 py-1.5 rounded-md text-xs font-medium {{ $active->status === $s ? 'bg-cadical-500 text-white' : 'border border-slate-200 hover:bg-slate-50' }}">{{ $s }}</button>
                    @endforeach
                </div>

                <h4 class="font-semibold text-sm text-slate-900 mb-2">Items</h4>
                <div class="space-y-2 mb-4">
                    @foreach ($active->orderItems as $item)
                        <div class="flex items-center justify-between text-sm border border-slate-100 rounded-lg p-2.5">
                            <span class="text-slate-700">{{ $item->product->name ?? 'Product' }} × {{ $item->quantity }}</span>
                            <span class="text-slate-900 font-medium">₦{{ number_format($item->price * $item->quantity, 0) }}</span>
                        </div>
                    @endforeach
                </div>

                <h4 class="font-semibold text-sm text-slate-900 mb-2">Tracking History</h4>
                <div class="space-y-2">
                    @foreach ($active->trackingEvents as $event)
                        <div class="text-xs border-l-2 border-cadical-500 pl-3 py-1">
                            <p class="font-semibold text-slate-900">{{ $event->status }}</p>
                            <p class="text-slate-500">{{ $event->message }}</p>
                            <p class="text-slate-400">{{ $event->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
