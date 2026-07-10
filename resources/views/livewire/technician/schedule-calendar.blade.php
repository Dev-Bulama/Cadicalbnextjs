<div class="p-4 space-y-4">
    <h1 class="text-xl font-bold text-slate-900">My Schedule</h1>

    <div class="bg-white rounded-2xl border border-slate-100 p-4">
        <div class="flex items-center justify-between mb-4">
            <button wire:click="prevMonth" class="p-1.5 rounded-lg hover:bg-slate-100"><i data-lucide="chevron-left" class="w-[18px] h-[18px]"></i></button>
            <p class="font-semibold text-sm text-slate-900">{{ $monthStart->format('F Y') }}</p>
            <button wire:click="nextMonth" class="p-1.5 rounded-lg hover:bg-slate-100"><i data-lucide="chevron-right" class="w-[18px] h-[18px]"></i></button>
        </div>

        <div class="grid grid-cols-7 mb-1">
            @foreach (['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $d)
                <div class="text-center text-xs text-slate-400 py-1 font-medium">{{ $d }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-0.5">
            @for ($i = 0; $i < $firstDayOfWeek; $i++)
                <div></div>
            @endfor
            @for ($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $key = $monthStart->copy()->day($d)->format('Y-m-d');
                    $dayJobs = $jobs->get($key, collect());
                    $isToday = $d === (int) now()->day && $month === (int) now()->month && $year === (int) now()->year;
                    $isSelected = $d === $selectedDay;
                @endphp
                <button wire:click="selectDay({{ $d }})" class="aspect-square flex flex-col items-center justify-center rounded-lg text-sm transition-colors relative {{ $isSelected ? 'bg-cadical-500 text-white' : ($isToday ? 'bg-cadical-500/10 text-cadical-500 font-semibold' : 'hover:bg-slate-100') }}">
                    <span>{{ $d }}</span>
                    @if ($dayJobs->isNotEmpty())
                        <div class="flex gap-0.5 mt-0.5">
                            @foreach ($dayJobs->take(3) as $j)
                                <div class="w-1 h-1 rounded-full {{ $isSelected ? 'bg-white' : 'bg-cadical-500' }}"></div>
                            @endforeach
                        </div>
                    @endif
                </button>
            @endfor
        </div>
    </div>

    <div>
        <h2 class="text-sm font-semibold text-slate-900 mb-2">
            {{ $monthStart->copy()->day($selectedDay)->format('F j, Y') }}
            @if ($selectedJobs->isNotEmpty())
                <span class="ml-2 bg-slate-100 text-slate-600 text-[10px] px-1.5 py-0.5 rounded-full">{{ $selectedJobs->count() }} job{{ $selectedJobs->count() !== 1 ? 's' : '' }}</span>
            @endif
        </h2>

        @if ($selectedJobs->isEmpty())
            <div class="text-center py-8 text-slate-400 text-sm">
                <i data-lucide="calendar" class="w-6 h-6 mx-auto mb-2 opacity-40"></i>
                No jobs scheduled for this day
            </div>
        @else
            <div class="space-y-2">
                @foreach ($selectedJobs as $job)
                    <div class="bg-white rounded-xl border border-slate-100 p-3 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-cadical-500/10 flex items-center justify-center shrink-0">
                            <i data-lucide="wrench" class="w-3.5 h-3.5 text-cadical-500"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900">{{ $job->booking->equipment_name ?? 'Equipment' }}</p>
                            <p class="text-xs text-slate-400">{{ str_replace('_', ' ', $job->status) }} · {{ $job->scheduled_at->format('h:i A') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
