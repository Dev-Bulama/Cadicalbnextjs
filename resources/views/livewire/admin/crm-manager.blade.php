@php
    $tabs = ['overview' => 'Overview', 'logs' => 'Sync Logs', 'mappings' => 'Field Mappings', 'automations' => 'Automations', 'failed-jobs' => 'Failed Jobs', 'webhooks' => 'Webhook Logs'];
    $statusColors = ['success' => 'emerald', 'partial' => 'amber', 'failed' => 'red', 'running' => 'cadical'];
@endphp
<div>
    <div class="flex gap-1 mb-6 border-b border-slate-200 overflow-x-auto">
        @foreach ($tabs as $key => $label)
            <button wire:click="$set('tab', '{{ $key }}')" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap {{ $tab === $key ? 'border-cadical-500 text-cadical-500' : 'border-transparent text-slate-500 hover:text-slate-900' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Overview --}}
    @if ($tab === 'overview')
        @if (! $connection)
            <div class="bg-white rounded-2xl border border-slate-100 p-6 max-w-lg">
                <p class="text-sm text-slate-500 mb-4">No Zoho CRM connection configured yet. Enter your Zoho API credentials to get started.</p>
                @if (! $showSetupForm)
                    <button wire:click="$set('showSetupForm', true)" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Configure Zoho Connection</button>
                @else
                    <form wire:submit="saveConnection" class="space-y-3">
                        <div><label class="text-xs font-medium text-slate-500">Client ID</label><input wire:model="clientId" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">@error('clientId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror</div>
                        <div><label class="text-xs font-medium text-slate-500">Client Secret</label><input type="password" wire:model="clientSecret" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">@error('clientSecret') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror</div>
                        <div><label class="text-xs font-medium text-slate-500">Redirect URI</label><input wire:model="redirectUri" placeholder="{{ url('/admin/integrations/crm/zoho/callback') }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">@error('redirectUri') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror</div>
                        <div class="flex gap-2 pt-1">
                            <button type="submit" class="px-4 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Save</button>
                            <button type="button" wire:click="$set('showSetupForm', false)" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Cancel</button>
                        </div>
                    </form>
                @endif
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-6">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cadical-500/10 flex items-center justify-center">
                            <i data-lucide="plug" class="w-5 h-5 text-cadical-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-900">Zoho CRM</p>
                            <p class="text-xs text-slate-400">Customer, order and RFQ synchronization</p>
                        </div>
                    </div>
                    <x-admin.badge :color="$connection->is_connected ? 'emerald' : 'slate'">{{ $connection->is_connected ? 'Connected' : 'Not Connected' }}</x-admin.badge>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 text-sm">
                    <div><p class="text-xs text-slate-400">Health Score</p><p class="text-slate-900 font-medium">{{ $connection->health_score }}</p></div>
                    <div><p class="text-xs text-slate-400">Sync Enabled</p><p class="text-slate-900 font-medium">{{ $connection->sync_enabled ? 'Yes' : 'No' }}</p></div>
                    <div><p class="text-xs text-slate-400">Last Sync</p><p class="text-slate-900 font-medium">{{ $connection->last_sync_at?->diffForHumans() ?? 'Never' }}</p></div>
                    <div><p class="text-xs text-slate-400">Sync Interval</p><p class="text-slate-900 font-medium">{{ $connection->sync_interval }}</p></div>
                </div>

                @if ($connection->last_error)
                    <div class="mt-4 bg-red-50 border border-red-100 rounded-lg p-3 text-xs text-red-600">{{ $connection->last_error }}</div>
                @endif

                <div class="flex items-center gap-2 mt-6 flex-wrap">
                    @if (! $connection->is_connected)
                        <a href="{{ url('/admin/integrations/crm/zoho/authorize') }}" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold flex items-center">Connect to Zoho</a>
                    @else
                        <button wire:click="testConnection" class="h-9 px-4 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Test Connection</button>
                        <button wire:click="syncAll" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Sync All</button>
                        <button wire:click="disconnect" wire:confirm="Disconnect from Zoho CRM?" class="h-9 px-4 text-red-500 hover:bg-red-50 rounded-lg text-sm font-semibold">Disconnect</button>
                    @endif
                </div>
            </div>

            @if ($connection->is_connected)
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach (['contacts' => 'users', 'accounts' => 'building-2', 'deals' => 'handshake', 'tickets' => 'life-buoy', 'leads' => 'target'] as $entity => $icon)
                        <button wire:click="syncEntity('{{ $entity }}')" class="bg-white rounded-xl border border-slate-100 p-4 text-center hover:border-cadical-500/50 transition-colors">
                            <i data-lucide="{{ $icon }}" class="w-5 h-5 text-cadical-500 mx-auto mb-2"></i>
                            <p class="text-xs font-semibold text-slate-900 capitalize">Sync {{ $entity }}</p>
                        </button>
                    @endforeach
                </div>
            @endif
        @endif
    @endif

    {{-- Sync Logs --}}
    @if ($tab === 'logs')
        <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                        <th class="px-4 py-3 font-medium">Entity</th>
                        <th class="px-4 py-3 font-medium">Direction</th>
                        <th class="px-4 py-3 font-medium">Records</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Started</th>
                        <th class="px-4 py-3 font-medium">Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($syncLogs as $log)
                        <tr class="border-b border-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-900 capitalize">{{ $log->entity }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $log->direction }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $log->records_synced }}/{{ $log->records_total }} @if($log->records_failed) <span class="text-red-500">({{ $log->records_failed }} failed)</span> @endif</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$statusColors[$log->status] ?? 'slate'">{{ $log->status }}</x-admin.badge></td>
                            <td class="px-4 py-3 text-slate-500">{{ $log->started_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $log->duration_ms ? number_format($log->duration_ms).'ms' : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-slate-400">No sync activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- Field Mappings --}}
    @if ($tab === 'mappings')
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-slate-500">{{ $mappings->count() }} field mappings configured</p>
            @if ($connection)
                <button wire:click="$set('showMappingForm', true)" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Add Mapping</button>
            @endif
        </div>

        @if ($showMappingForm)
            <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <select wire:model="mapEntity" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        @foreach (['contact', 'account', 'deal', 'lead', 'ticket'] as $e)
                            <option value="{{ $e }}">{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                    <input wire:model="mapCadicalField" placeholder="Cadical field" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <input wire:model="mapCrmField" placeholder="CRM field" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <select wire:model="mapDirection" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        <option value="both">Both</option>
                        <option value="tocrm">To CRM</option>
                        <option value="fromcrm">From CRM</option>
                    </select>
                </div>
                <div class="flex gap-2 mt-3">
                    <button wire:click="saveMapping" class="px-4 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Save</button>
                    <button wire:click="$set('showMappingForm', false)" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Cancel</button>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                        <th class="px-4 py-3 font-medium">Entity</th>
                        <th class="px-4 py-3 font-medium">Cadical Field</th>
                        <th class="px-4 py-3 font-medium">CRM Field</th>
                        <th class="px-4 py-3 font-medium">Direction</th>
                        <th class="px-4 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mappings as $m)
                        <tr class="border-b border-slate-50">
                            <td class="px-4 py-3 capitalize text-slate-900 font-medium">{{ $m->entity }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $m->cadical_field }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $m->crm_field }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $m->direction }}</td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="deleteMapping({{ $m->id }})" class="text-red-500 hover:bg-red-50 rounded-md px-2 py-1 text-xs font-semibold">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-slate-400">No field mappings configured.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- Automation Rules --}}
    @if ($tab === 'automations')
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-slate-500">{{ $rules->count() }} automation rules</p>
            @if ($connection)
                <button wire:click="$set('showRuleForm', true)" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Add Rule</button>
            @endif
        </div>

        @if ($showRuleForm)
            <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-4 space-y-3">
                <input wire:model="ruleName" placeholder="Rule name" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                <textarea wire:model="ruleDescription" placeholder="Description (optional)" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></textarea>
                <div class="grid grid-cols-2 gap-3">
                    <select wire:model="ruleTriggerEvent" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        @foreach (['order_completed', 'rfq_submitted', 'booking_created', 'user_inactive'] as $t)
                            <option value="{{ $t }}">{{ str_replace('_', ' ', ucfirst($t)) }}</option>
                        @endforeach
                    </select>
                    <select wire:model="ruleActionType" class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        @foreach (['create_deal', 'create_lead', 'create_contact', 'create_ticket', 'update_stage'] as $a)
                            <option value="{{ $a }}">{{ str_replace('_', ' ', ucfirst($a)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button wire:click="saveRule" class="px-4 py-2 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold">Save</button>
                    <button wire:click="$set('showRuleForm', false)" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">Cancel</button>
                </div>
            </div>
        @endif

        <div class="space-y-3">
            @forelse ($rules as $rule)
                <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-sm text-slate-900">{{ $rule->name }}</p>
                            <x-admin.badge :color="$rule->is_active ? 'emerald' : 'slate'">{{ $rule->is_active ? 'Active' : 'Paused' }}</x-admin.badge>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">{{ str_replace('_', ' ', $rule->trigger_event) }} → {{ str_replace('_', ' ', $rule->action_type) }}</p>
                        @if ($rule->description)
                            <p class="text-xs text-slate-500 mt-1">{{ $rule->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button wire:click="toggleRule({{ $rule->id }})" class="h-7 px-2.5 border border-slate-200 rounded-md text-xs font-semibold hover:bg-slate-50">{{ $rule->is_active ? 'Pause' : 'Activate' }}</button>
                        <button wire:click="deleteRule({{ $rule->id }})" class="h-7 px-2.5 text-red-500 hover:bg-red-50 rounded-md text-xs font-semibold">Delete</button>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center text-slate-400">No automation rules configured.</div>
            @endforelse
        </div>
    @endif

    {{-- Failed Jobs --}}
    @if ($tab === 'failed-jobs')
        <div class="space-y-3">
            @forelse ($failedJobs as $job)
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-sm text-slate-900 capitalize">{{ $job->entity }} — {{ $job->operation }}</p>
                                <x-admin.badge color="red">{{ $job->status }}</x-admin.badge>
                            </div>
                            <p class="text-xs text-red-500 mt-1">{{ $job->error_message }}</p>
                            <p class="text-xs text-slate-400 mt-1">Retry {{ $job->retry_count }}/{{ $job->max_retries }}</p>
                        </div>
                        <button wire:click="retryFailedJob({{ $job->id }})" class="h-8 px-3 bg-cadical-500 hover:bg-cadical-700 text-white rounded-md text-xs font-semibold">Retry</button>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center text-slate-400">No failed jobs.</div>
            @endforelse
        </div>
    @endif

    {{-- Webhook Logs --}}
    @if ($tab === 'webhooks')
        <div class="bg-white rounded-2xl border border-slate-100 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-left text-xs text-slate-400 uppercase tracking-wide">
                        <th class="px-4 py-3 font-medium">Event</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Received</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($webhookLogs as $log)
                        <tr class="border-b border-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $log->event }}</td>
                            <td class="px-4 py-3"><x-admin.badge :color="$log->status === 'processed' ? 'emerald' : ($log->status === 'failed' ? 'red' : 'amber')">{{ $log->status }}</x-admin.badge></td>
                            <td class="px-4 py-3 text-slate-500">{{ $log->received_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-12 text-center text-slate-400">No webhook events received yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
