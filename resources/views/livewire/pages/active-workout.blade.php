<?php
use function Livewire\Volt\{state, computed};
use App\Models\Workout;
use App\Models\WorkoutSet;

state(['workoutId']);

$workout = computed(fn() => Workout::with(['workoutExercises.exercise', 'workoutExercises.sets'])->find($this->workoutId));

$updateSet = function ($setId, $weight, $reps) {
    WorkoutSet::find($setId)->update(['weight' => $weight, 'reps' => $reps]);
};

$finishWorkout = function () {
    $this->dispatch('finish-workout', id: $this->workoutId);
};

$toggleSet = function ($setId) {
    $set = WorkoutSet::find($setId);
    $set->is_completed = !$set->is_completed;
    $set->save();

    // Logica Server: invia il segnale al browser
    if ($set->is_completed) {
        $this->dispatch('start-timer', seconds: $set->rest_time ?? 90);
    } else {
        $this->dispatch('stop-timer');
    }
};
?>

{{-- Logica Client (Alpine) --}}
<div class="min-h-screen relative" x-data="{
    timeLeft: 0,
    totalTime: 0,
    interval: null,

    start(seconds) {
        clearInterval(this.interval);
        this.timeLeft = seconds;
        this.totalTime = seconds;
        this.interval = setInterval(() => {
            if (this.timeLeft > 0) {
                this.timeLeft--;
            } else {
                this.stop();
            }
        }, 1000);
    },

    stop() {
        clearInterval(this.interval);
        this.timeLeft = 0;
    },

    formatTime(s) {
        let m = Math.floor(s / 60);
        let sec = s % 60;
        return m + ':' + (sec < 10 ? '0' : '') + sec;
    }
}" x-on:start-timer.window="start($event.detail.seconds)"
    x-on:stop-timer.window="stop()">

    {{-- BARRA TIMER --}}
    <div x-show="timeLeft > 0" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="-translate-y-full"
        class="fixed top-0 left-0 right-0 z-[100] bg-slate-900 border-b border-yellow-500/30 shadow-2xl overflow-hidden">

        <div class="absolute inset-0 bg-yellow-500/10 transition-all duration-1000 ease-linear"
            :style="`width: ${(timeLeft / totalTime) * 100}%`">
        </div>
        <div class="relative px-6 py-3 flex justify-between items-center h-16">
            <div class="flex items-center gap-4">
                <span class="text-xl font-black italic tabular-nums text-white" x-text="formatTime(timeLeft)"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Recupero</span>
            </div>
            <button @click="stop()"
                class="bg-yellow-500 text-black px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">Salta</button>
        </div>
    </div>

    {{-- CONTENUTO --}}
    <div class="p-4 pb-32 transition-all duration-500" :class="timeLeft > 0 ? 'pt-24' : 'pt-4'">
        <div class="flex justify-between items-start mb-8">
            <div wire:click="$parent.setTab('training')" class="cursor-pointer group">
                <h1 class="text-3xl font-black uppercase italic tracking-tighter text-yellow-500">
                    {{ $this->workout->name }}</h1>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Sessione attiva (Riduci)
                </p>
            </div>
            <button wire:click="finishWorkout"
                class="bg-red-600/10 text-red-500 border border-red-500/20 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">Termina</button>
        </div>

        <div class="space-y-10">
            @foreach ($this->workout->workoutExercises as $wExercise)
                <div class="bg-slate-900/50 rounded-3xl p-5 border border-slate-800">
                    <h2 class="text-lg font-bold text-white mb-4"><span
                            class="text-yellow-500">#{{ $loop->iteration }}</span> {{ $wExercise->exercise->name }}</h2>
                    <div class="space-y-3">
                        @foreach ($wExercise->sets as $set)
                            <div
                                class="grid grid-cols-4 items-center bg-black/40 rounded-2xl p-3 border {{ $set->is_completed ? 'border-yellow-500/50' : 'border-transparent' }}">
                                <div class="text-sm font-bold text-slate-400">{{ $set->set_number }}</div>
                                <input type="number"
                                    wire:change="updateSet({{ $set->id }}, $event.target.value, {{ $set->reps }})"
                                    value="{{ (float) $set->weight }}"
                                    class="bg-transparent text-white w-16 text-sm font-bold">
                                <input type="number"
                                    wire:change="updateSet({{ $set->id }}, {{ $set->weight }}, $event.target.value)"
                                    value="{{ $set->reps }}"
                                    class="bg-transparent text-white w-16 text-sm font-bold">
                                <div class="text-right">
                                    <button wire:click="toggleSet({{ $set->id }})"
                                        class="w-10 h-10 rounded-xl flex items-center justify-center {{ $set->is_completed ? 'bg-yellow-500 text-black' : 'bg-slate-800 text-slate-500' }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
