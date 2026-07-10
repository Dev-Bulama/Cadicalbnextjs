@php
    $statusColors = ['DRAFT' => 'slate', 'SUBMITTED' => 'amber', 'NEGOTIATING' => 'amber', 'APPROVED' => 'cadical', 'PROCESSING' => 'cadical', 'SHIPPED' => 'amber', 'DELIVERED' => 'emerald', 'CANCELLED' => 'red'];
@endphp
<div>
    <div class="flex gap-3 mb-6">
        <select wire:model.live="statusFilter" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
            <option value="">All statuses</option>
            @foreach (['DRAFT', 'SUBMITTED', 'NEGOTIATING', 'APPROVED', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED'] as $s)
                <option value="{{ $s }}">{{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                    <th class="px-4 py-3 font-medium">Order</th>
                    <th class="px-4 py-3 font-medium">Organization</th>
                    <th class="px-4 py-3 font-medium">Amount</th>
                    <th class="px-4 py-3 font-medium">Delivery</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr wire:key="bo-{{ $order->id }}" class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $order->bulk_code }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $order->organization ?? $order->contact_name }}</td>
                        <td class="px-4 py-3 text-slate-600">₦{{ number_format($order->final_amount, 0) }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $order->delivery_date?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3"><x-admin.badge :color="$statusColors[$order->status] ?? 'slate'">{{ $order->status }}</x-admin.badge></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-slate-400">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
