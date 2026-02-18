<?php
use function Livewire\Volt\{state};
use Carbon\Carbon;

state(['workout']);
?>

<div class="bg-slate-900/50 border border-slate-800 p-5 rounded-3xl active:scale-[0.98] transition-transform">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">
                @php
                    $date = $workout->completed_at;
                    $formattedDate =
                        $date instanceof \Carbon\Carbon
                            ? $date->format('d M Y')
                            : Carbon::parse($date)->format('d M Y');
                @endphp
                {{ $formattedDate }}
            </p>
            <h3 class="text-lg font-black text-white italic uppercase leading-tight">
                {{ $workout->name }}
            </h3>
        </div>
        <div class="text-slate-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </div>

    <div class="mt-4 pt-3 border-t border-slate-800/50">
        <div class="flex flex-wrap gap-x-2 gap-y-1">
            @foreach ($workout->workoutExercises as $we)
                <span class="text-[11px] text-slate-400 font-medium whitespace-nowrap">
                    {{ $we->exercise->name }}@if (!$loop->last)
                        <span class="text-slate-700">•</span>
                    @endif
                </span>
            @endforeach
        </div>
    </div>
</div>
