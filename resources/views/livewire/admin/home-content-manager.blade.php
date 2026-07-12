@php
    $schema = $sections[$activeSection];
    $fieldLabel = fn ($key) => ucwords(str_replace(['_', 'cta1 ', 'cta2 '], [' ', 'Primary Button ', 'Secondary Button '], $key));
@endphp
<div class="flex gap-6 items-start">
    <div class="w-56 shrink-0 bg-white rounded-2xl border border-slate-100 p-2 sticky top-6">
        @foreach ($sections as $key => $s)
            <button wire:click="switchSection('{{ $key }}')" wire:key="nav-{{ $key }}" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors {{ $activeSection === $key ? 'bg-cadical-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                {{ $s['label'] }}
            </button>
        @endforeach
    </div>

    <div class="flex-1 min-w-0 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-lg text-slate-900">{{ $schema['label'] }}</h2>
                <p class="text-xs text-slate-400">Changes apply to the live homepage immediately after saving.</p>
            </div>
            <button wire:click="save" wire:loading.attr="disabled" class="h-9 px-4 bg-cadical-500 hover:bg-cadical-700 text-white rounded-lg text-sm font-semibold disabled:opacity-60">
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>

        @if (! empty($schema['metaFields']))
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h3 class="font-semibold text-sm text-slate-900 mb-4">Section Text</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($schema['metaFields'] as $field => $type)
                        <div class="{{ $type === 'textarea' ? 'sm:col-span-2' : '' }}">
                            <label class="text-xs font-medium text-slate-500">{{ $fieldLabel($field) }}</label>
                            @if ($type === 'textarea')
                                <textarea wire:model="meta.{{ $field }}" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></textarea>
                            @elseif ($type === 'image')
                                <input type="text" wire:model="meta.{{ $field }}" placeholder="e.g. mri.jpeg or /storage/home/xyz.jpg" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                                <input type="file" wire:model="metaImages.{{ $field }}" accept="image/*" class="w-full text-xs text-slate-500 mt-1.5">
                                <div wire:loading wire:target="metaImages.{{ $field }}" class="text-xs text-cadical-500 mt-1">Uploading…</div>
                                @if ($meta[$field] ?? false)
                                    <img src="{{ str_starts_with($meta[$field], '/storage') ? url($meta[$field]) : asset($meta[$field]) }}" class="mt-2 h-16 rounded-lg border border-slate-100 object-cover">
                                @endif
                            @else
                                <input type="text" wire:model="meta.{{ $field }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (! empty($schema['itemFields']))
            <div class="space-y-4">
                @foreach ($items as $i => $item)
                    <div wire:key="item-{{ $activeSection }}-{{ $i }}" class="bg-white rounded-2xl border border-slate-100 p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-sm text-slate-900">{{ $schema['itemLabel'] }} {{ $i + 1 }}</h4>
                            <div class="flex items-center gap-1">
                                <button wire:click="moveItem({{ $i }}, -1)" class="w-7 h-7 flex items-center justify-center border border-slate-200 rounded-md hover:bg-slate-50 disabled:opacity-30" @if($i === 0) disabled @endif>
                                    <i data-lucide="chevron-up" class="w-3.5 h-3.5"></i>
                                </button>
                                <button wire:click="moveItem({{ $i }}, 1)" class="w-7 h-7 flex items-center justify-center border border-slate-200 rounded-md hover:bg-slate-50 disabled:opacity-30" @if($i === count($items) - 1) disabled @endif>
                                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                </button>
                                <button wire:click="removeItem({{ $i }})" wire:confirm="Remove this {{ strtolower($schema['itemLabel']) }}?" class="w-7 h-7 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-md">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($schema['itemFields'] as $field => $type)
                                <div class="{{ in_array($type, ['textarea', 'array']) ? 'sm:col-span-2' : '' }}">
                                    <label class="text-xs font-medium text-slate-500">{{ $fieldLabel($field) }}</label>

                                    @if ($type === 'textarea')
                                        <textarea wire:model="items.{{ $i }}.{{ $field }}" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1"></textarea>
                                    @elseif ($type === 'array')
                                        <textarea wire:model="items.{{ $i }}.{{ $field }}" rows="3" placeholder="One per line" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1 font-mono"></textarea>
                                    @elseif ($type === 'number')
                                        <input type="number" wire:model="items.{{ $i }}.{{ $field }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                                    @elseif ($type === 'boolean')
                                        <label class="flex items-center gap-2 mt-2">
                                            <input type="checkbox" wire:model="items.{{ $i }}.{{ $field }}" class="rounded border-slate-300">
                                            <span class="text-sm text-slate-700">Highlighted / featured</span>
                                        </label>
                                    @elseif ($type === 'image')
                                        <input type="text" wire:model="items.{{ $i }}.{{ $field }}" placeholder="e.g. mri.jpeg or /storage/home/xyz.jpg" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                                        <input type="file" wire:model="itemImages.{{ $i }}" accept="image/*" class="w-full text-xs text-slate-500 mt-1.5">
                                        <div wire:loading wire:target="itemImages.{{ $i }}" class="text-xs text-cadical-500 mt-1">Uploading…</div>
                                        @if ($item[$field] ?? false)
                                            <img src="{{ str_starts_with($item[$field], '/storage') ? url($item[$field]) : asset($item[$field]) }}" class="mt-2 h-16 rounded-lg border border-slate-100 object-cover">
                                        @endif
                                    @else
                                        <input type="text" wire:model="items.{{ $i }}.{{ $field }}" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm mt-1">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <button wire:click="addItem" class="w-full py-3 border-2 border-dashed border-slate-200 rounded-2xl text-sm font-semibold text-slate-500 hover:border-cadical-500 hover:text-cadical-500 transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add {{ $schema['itemLabel'] }}
                </button>
            </div>
        @endif

        @if (empty($schema['metaFields']) && empty($schema['itemFields']))
            <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center text-slate-400">Nothing to configure for this section.</div>
        @endif
    </div>
</div>
