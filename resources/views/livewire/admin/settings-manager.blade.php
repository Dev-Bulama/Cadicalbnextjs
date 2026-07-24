@php
    $schema = $groups[$activeGroup];
@endphp
<div class="flex gap-6 items-start">
    <div class="w-56 shrink-0 bg-white rounded-2xl border border-slate-100 p-2 sticky top-6">
        @foreach ($groups as $key => $g)
            <button wire:click="switchGroup('{{ $key }}')" wire:key="nav-{{ $key }}" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors {{ $activeGroup === $key ? 'bg-cadical-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                {{ $g['label'] }}
            </button>
        @endforeach
    </div>

    <div class="flex-1 min-w-0 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-lg text-slate-900">{{ $schema['label'] }}</h2>
                <p class="text-xs text-slate-400">Saved values take effect immediately — no server restart or redeploy needed.</p>
            </div>
            <button wire:click="save" wire:loading.attr="disabled" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold disabled:opacity-60">
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($schema['fields'] as $configKey => $field)
                    <div wire:key="field-{{ $activeGroup }}-{{ $loop->index }}" class="{{ in_array($field['type'], ['image', 'code']) ? 'sm:col-span-2' : '' }}">
                        <label class="text-xs font-medium text-slate-500">{{ $field['label'] }}</label>

                        @if ($field['type'] === 'select')
                            <select wire:model="values.{{ $loop->index }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1 bg-white">
                                @foreach ($field['options'] as $val => $optLabel)
                                    <option value="{{ $val }}">{{ $optLabel }}</option>
                                @endforeach
                            </select>
                        @elseif ($field['type'] === 'password')
                            <input type="password" wire:model="values.{{ $loop->index }}" autocomplete="new-password" placeholder="{{ ($configured[$loop->index] ?? false) ? '•••••••• (saved — leave blank to keep)' : 'Not set' }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                            <p class="text-[11px] mt-1 {{ ($configured[$loop->index] ?? false) ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ ($configured[$loop->index] ?? false) ? 'Configured' : 'Not configured' }}
                            </p>
                        @elseif ($field['type'] === 'image')
                            <div class="flex items-center gap-4 mt-1">
                                @if ($values[$loop->index] ?? false)
                                    <img src="{{ \App\Models\HomeSection::mediaUrl($values[$loop->index]) }}" class="h-14 w-14 rounded-lg border border-slate-100 object-contain bg-slate-50 shrink-0">
                                @else
                                    <div class="h-14 w-14 rounded-lg border border-dashed border-slate-200 flex items-center justify-center text-[10px] text-slate-300 shrink-0">Default</div>
                                @endif
                                <div class="flex-1 min-w-0 space-y-1.5">
                                    <input type="text" wire:model="values.{{ $loop->index }}" placeholder="/storage/... or a full https:// image URL" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                                    <input type="file" wire:model="valueFiles.{{ $loop->index }}" accept="image/*" class="w-full text-xs text-slate-500">
                                    <div wire:loading wire:target="valueFiles.{{ $loop->index }}" class="text-xs text-cadical-500">Uploading…</div>
                                </div>
                            </div>
                        @elseif ($field['type'] === 'boolean')
                            <label class="flex items-center gap-2 mt-2">
                                <input type="checkbox" wire:model="values.{{ $loop->index }}" class="rounded border-slate-300">
                                <span class="text-sm text-slate-700">{{ $field['checkboxLabel'] ?? 'Enabled' }}</span>
                            </label>
                        @elseif ($field['type'] === 'code')
                            <textarea wire:model="values.{{ $loop->index }}" rows="4" placeholder="{{ $field['placeholder'] ?? '' }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-xs font-mono mt-1"></textarea>
                        @elseif ($field['type'] === 'number')
                            <input type="number" wire:model="values.{{ $loop->index }}" min="0" step="1000" placeholder="{{ $field['placeholder'] ?? '' }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                        @else
                            <input type="text" wire:model="values.{{ $loop->index }}" placeholder="{{ $field['placeholder'] ?? '' }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                        @endif

                        @if (! empty($field['hint']))
                            <p class="text-[11px] text-slate-400 mt-1">{{ $field['hint'] }}</p>
                        @endif
                        @if (! empty($field['env']))
                            <p class="text-[11px] text-slate-300 mt-1">Overrides env: {{ $field['env'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
