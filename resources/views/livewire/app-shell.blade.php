<?php
use function Livewire\Volt\{state, on, mount};
use App\Models\Template;
use App\Models\Workout;
use Illuminate\Support\Facades\DB;

mount(function () {
    $ongoingWorkout = Workout::where('user_id', auth()->id())
        ->whereNull('completed_at')
        ->latest()
        ->first();

    if ($ongoingWorkout) {
        $this->activeWorkoutId = $ongoingWorkout->id;
    }
});

state([
    'currentTab' => 'training',
    'activeWorkoutId' => null,
]);

on([
    'template-saved' => function () {}, // Placeholder per ricaricamento

    // Questo ascolta l'evento lanciato dalla Navbar e dalla Floating Bar
    'set-tab' => function ($tab) {
        $this->currentTab = $tab;
    },

    'delete-template' => function ($id) {
        \App\Models\Template::find($id)?->delete();
    },

    'start-workout' => function ($templateId) {
        if ($this->activeWorkoutId) {
            return;
        }

        DB::transaction(function () use ($templateId) {
            $template = Template::with('exercises.sets')->findOrFail($templateId);
            $workout = Workout::create([
                'user_id' => auth()->id(),
                'template_id' => $template->id,
                'name' => $template->name,
                'started_at' => now(),
            ]);

            foreach ($template->exercises as $index => $tEx) {
                $wEx = \App\Models\WorkoutExercise::create([
                    'workout_id' => $workout->id,
                    'exercise_id' => $tEx->exercise_id,
                    'position' => $index,
                ]);
                foreach ($tEx->sets as $sIndex => $tSet) {
                    \App\Models\WorkoutSet::create([
                        'workout_exercise_id' => $wEx->id,
                        'set_number' => $sIndex + 1,
                        'weight' => $tSet->weight ?? 0,
                        'reps' => $tSet->reps ?? 0,
                        'is_completed' => false,
                    ]);
                }
            }
            $this->activeWorkoutId = $workout->id;
            $this->currentTab = 'active-workout';
        });
    },

    'finish-workout' => function () {
        $workout = Workout::find($this->activeWorkoutId);
        if ($workout) {
            $workout->update(['completed_at' => now()]);
        }
        $this->activeWorkoutId = null;
        $this->currentTab = 'training';
    },
]);
?>

<div class="min-h-screen bg-black text-white">
    <main class="pb-24">
        @if ($currentTab === 'training')
            <livewire:pages.training />
        @elseif ($currentTab === 'active-workout' && $activeWorkoutId)
            <livewire:pages.active-workout :workout-id="$activeWorkoutId" :key="'workout-' . $activeWorkoutId" />
        @elseif ($currentTab === 'create-template')
            <livewire:pages.create-template />
        @elseif ($currentTab === 'exercises')
            <livewire:pages.exercises />
        @elseif ($currentTab === 'profile')
            <div class="p-10 text-center text-slate-500 font-bold uppercase tracking-widest">Profilo in arrivo</div>
        @elseif ($currentTab === 'history')
            <livewire:pages.history />
        @elseif ($currentTab === 'social')
            <div class="p-10 text-center text-slate-500 font-bold uppercase tracking-widest">Social in arrivo</div>
        @endif
    </main>

    {{-- BARRA GIALLA FLOTTANTE (Mantienila qui per ora, è logicamente legata allo stato attivo della shell) --}}
    @if ($activeWorkoutId && $currentTab !== 'active-workout')
        <div wire:click="$dispatch('set-tab', { tab: 'active-workout' })"
            class="fixed bottom-20 left-4 right-4 bg-yellow-500 text-black p-4 rounded-3xl shadow-[0_0_20px_rgba(234,179,8,0.3)] flex justify-between items-center z-[100] cursor-pointer animate-in slide-in-from-bottom-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-500 animate-pulse" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase opacity-60">Sessione Attiva</p>
                    <p class="text-sm font-black italic">Torna ad allenarti</p>
                </div>
            </div>
            <div class="bg-black/10 p-2 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" />
                </svg></div>
        </div>
    @endif

    {{-- NAVBAR COMPONENT (Eccolo, pulitissimo) --}}
    <livewire:components.nav-bar :current-tab="$currentTab" :active-workout-id="$activeWorkoutId" />

    <livewire:components.modal-confirm />
</div>
