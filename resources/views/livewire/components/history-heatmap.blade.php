<?php
use function Livewire\Volt\{state, computed};
use App\Models\Workout;
use Carbon\Carbon;

state([
    'selectedMonth' => (int) now()->month,
    'selectedYear' => (int) now()->year,
    'showSelector' => false,
    'previewWorkout' => null,
]);

// Funzione per le freccette (Mese precedente/successivo)
$changeMonth = function ($direction) {
    $current = Carbon::create($this->selectedYear, $this->selectedMonth, 1);
    $direction === 'next' ? $current->addMonth() : $current->subMonth();
    $this->selectedMonth = $current->month;
    $this->selectedYear = $current->year;
};

// Selezione diretta dal pannello
$selectMonth = function ($m) {
    $this->selectedMonth = $m;
    $this->showSelector = false;
};

$changeYear = function ($delta) {
    $this->selectedYear += $delta;
};

$showPreview = function ($dateKey) {
    $this->previewWorkout = null;
    $workout = Workout::where('user_id', auth()->id())
        ->whereNotNull('completed_at')
        ->whereDate('completed_at', $dateKey)
        ->with('workoutExercises.exercise')
        ->first();
    if ($workout) {
        $this->previewWorkout = $workout;
    }
};

$monthData = computed(function () {
    Carbon::setLocale('it');
    $targetDate = Carbon::create($this->selectedYear, $this->selectedMonth, 1);
    $daysInMonth = $targetDate->daysInMonth;
    $firstDayOffset = $targetDate->dayOfWeekIso - 1;

    $activities = Workout::where('user_id', auth()->id())
        ->whereNotNull('completed_at')
        ->whereYear('completed_at', $this->selectedYear)
        ->whereMonth('completed_at', $this->selectedMonth)
        ->get()
        ->keyBy(fn($w) => $w->completed_at->format('Y-m-d'));

    $days = [];
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $currentDate = Carbon::create($this->selectedYear, $this->selectedMonth, $d);
        $key = $currentDate->format('Y-m-d');
        $days[] = [
            'number' => $d,
            'date_key' => $key,
            'has_workout' => $activities->has($key),
            'is_today' => $currentDate->isToday(),
            'is_future' => $currentDate->isFuture(),
        ];
    }

    return [
        'name' => $targetDate->translatedFormat('F'),
        'year' => $this->selectedYear,
        'days' => $days,
        'offset' => $firstDayOffset,
        'count' => $activities->count(),
    ];
});
?>

<div class="bg-slate-900/40 p-5 rounded-3xl border border-slate-800/50 relative" x-data="{ openPreview: false }"
    @click.away="openPreview = false; $wire.set('previewWorkout', null)"
    wire:key="heatmap-{{ $selectedMonth }}-{{ $selectedYear }}">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <button wire:click="changeMonth('prev')" class="p-2 bg-slate-800 rounded-xl text-slate-400 active:scale-90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            {{-- Click sul testo per aprire il selettore --}}
            <button wire:click="$set('showSelector', {{ !$showSelector ? 'true' : 'false' }})"
                class="text-center min-w-[100px] group">
                <h3
                    class="text-sm font-black uppercase italic tracking-tighter text-white group-active:text-yellow-500 transition-colors">
                    {{ $this->monthData['name'] }}
                </h3>
                <p
                    class="text-[9px] font-bold text-yellow-500 uppercase tracking-widest mt-1 flex items-center justify-center gap-1">
                    {{ $this->monthData['year'] }}
                    <svg class="w-2 h-2 transition-transform {{ $showSelector ? 'rotate-180' : '' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                    </svg>
                </p>
            </button>

            <button wire:click="changeMonth('next')" class="p-2 bg-slate-800 rounded-xl text-slate-400 active:scale-90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        <div class="bg-yellow-500 text-black px-3 py-1 rounded-full text-[10px] font-black uppercase italic">
            {{ $this->monthData['count'] }} Wkt
        </div>
    </div>

    {{-- LABELS GIORNI --}}
    <div class="grid grid-cols-7 gap-1.5 mb-2 px-1 text-center">
        @foreach (['L', 'M', 'M', 'G', 'V', 'S', 'D'] as $label)
            <span class="text-[8px] font-black text-slate-600 uppercase">{{ $label }}</span>
        @endforeach
    </div>

    {{-- GRIGLIA HEATMAP --}}
    <div class="grid grid-cols-7 gap-1.5">
        @for ($i = 0; $i < $this->monthData['offset']; $i++)
            <div class="aspect-square opacity-0"></div>
        @endfor

        @foreach ($this->monthData['days'] as $day)
            <div @if ($day['has_workout']) wire:click="showPreview('{{ $day['date_key'] }}')" @click="openPreview = true" @endif
                class="aspect-square rounded-lg flex items-center justify-center relative transition-all duration-300
                {{ $day['has_workout'] ? 'bg-yellow-500 shadow-[0_0_12px_rgba(234,179,8,0.4)] scale-105 cursor-pointer active:scale-95' : 'bg-slate-800/60' }}
                {{ $day['is_today'] ? 'ring-2 ring-white ring-offset-2 ring-offset-black z-10' : '' }}
                {{ $day['is_future'] ? 'opacity-20' : '' }}">
                <span
                    class="text-[8px] font-black {{ $day['has_workout'] ? 'text-black' : 'text-slate-500' }}">{{ $day['number'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- MODALE ANTEPRIMA --}}
    <div x-show="openPreview" x-cloak
        class="absolute inset-x-4 top-20 z-[100] animate-in fade-in zoom-in-95 duration-200">
        <div class="relative shadow-2xl">
            @if ($previewWorkout)
                <livewire:components.history-card :workout="$previewWorkout" :key="'preview-' . $previewWorkout->id" />
                <button @click="openPreview = false; $wire.set('previewWorkout', null)"
                    class="absolute -top-3 -right-3 bg-white text-black rounded-full p-2 shadow-xl border-4 border-black active:scale-90 transition-transform">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- QUICK SELECT PANEL (Il pezzo mancante) --}}
    @if ($showSelector)
        <div class="absolute inset-0 z-[110] bg-black/95 rounded-3xl p-5 animate-in fade-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center mb-6">
                <button wire:click="changeYear(-1)" class="p-2 text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span class="text-xl font-black italic text-yellow-500 tracking-tighter">{{ $selectedYear }}</span>
                <button wire:click="changeYear(1)" class="p-2 text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-3 gap-2">
                @foreach (range(1, 12) as $m)
                    <button wire:click="selectMonth({{ $m }})"
                        class="py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all
                        {{ $m == $selectedMonth ? 'bg-yellow-500 text-black' : 'bg-slate-900 text-slate-400 hover:bg-slate-800' }}">
                        {{ Carbon::create()->month($m)->translatedFormat('M') }}
                    </button>
                @endforeach
            </div>

            <button wire:click="$set('showSelector', false)"
                class="w-full mt-6 py-2 text-[9px] font-bold text-slate-600 uppercase tracking-[0.3em] hover:text-white transition-colors">Chiudi</button>
        </div>
    @endif
</div>
