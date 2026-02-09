<?php
use function Livewire\Volt\{state, on};
use App\Models\Template;
use App\Models\Exercise;

state([
    'name' => '',
    'selectedExercises' => [],
    'showExerciseModal' => false,
]);

on([
    'exercise-selected' => function ($exerciseId) {
        $exercise = Exercise::find($exerciseId);
        if ($exercise) {
            $this->selectedExercises[] = [
                'id' => $exercise->id,
                'name' => $exercise->name,
                'sets' => [['type' => 'normal', 'reps' => 10, 'weight' => 0, 'rest' => 120]],
            ];
        }
        $this->showExerciseModal = false;
    },
]);

$addSet = function ($index) {
    $this->selectedExercises[$index]['sets'][] = [
        'type' => 'normal',
        'reps' => 10,
        'weight' => 0,
        'rest' => 120,
    ];
};

$removeSet = function ($exIndex, $setIndex) {
    unset($this->selectedExercises[$exIndex]['sets'][$setIndex]);
    $this->selectedExercises[$exIndex]['sets'] = array_values($this->selectedExercises[$exIndex]['sets']);
};

$removeExercise = function ($index) {
    unset($this->selectedExercises[$index]);
    $this->selectedExercises = array_values($this->selectedExercises);
};

$save = function () {
    // 1. Validazione dei dati
    $this->validate([
        'name' => 'required|min:3',
        'selectedExercises' => 'required|array|min:1',
    ]);

    // 2. Creazione del Template principale
    $template = Template::create([
        'user_id' => auth()->id(),
        'name' => $this->name,
    ]);

    // 3. Ciclo per salvare gli esercizi collegati (template_exercises)
    foreach ($this->selectedExercises as $index => $ex) {
        $te = $template->exercises()->create([
            'exercise_id' => $ex['id'],
            'position' => $index,
            'superset_id' => null,
        ]);

        // 4. Ciclo per salvare i set di ogni esercizio (template_sets)
        foreach ($ex['sets'] as $setIndex => $set) {
            $te->sets()->create([
                'set_number' => $setIndex + 1,
                'type' => $set['type'] ?? 'normal',
                'target_reps' => filled($set['reps'] ?? null) ? $set['reps'] : 0,
                'target_weight' => filled($set['weight'] ?? null) ? $set['weight'] : 0,
                'rest_time' => filled($set['rest'] ?? null) ? $set['rest'] : 120,
                'side' => 'both',
            ]);
        }
    }

    // 5. Reset dello stato locale del componente
    $this->selectedExercises = [];
    $this->name = '';

    // 6. GESTIONE REATTIVA (Senza ricaricare la pagina)
    // Notifichiamo alla dashboard di aggiornare la sua lista di template
    $this->dispatch('template-saved');

    // Cambiamo il tab nella Shell principale
    $this->dispatch('set-tab', tab: 'training');
};
?>

<div class="min-h-screen bg-black p-4 pb-32">
    <div class="flex justify-between items-center mb-6">
        <button wire:click="$parent.setTab('training')"
            class="text-slate-400 font-bold uppercase text-xs tracking-widest">Annulla</button>
        <button wire:click="save"
            class="bg-yellow-500 text-black px-6 py-2 rounded-full font-black uppercase text-xs shadow-[0_0_20px_rgba(234,179,8,0.3)]">Salva</button>
    </div>

    <input type="text" wire:model="name" placeholder="NOME DEL TEMPLATE"
        class="w-full bg-transparent border-none text-2xl font-black uppercase tracking-tighter focus:ring-0 placeholder:text-slate-900 p-0 mb-8 text-white">

    <div class="space-y-6">
        @foreach ($selectedExercises as $index => $ex)
            {{-- CHIAVE DINAMICA: importante aggiungere count dei sets alla key --}}
            <livewire:components.editable-exercise-card :ex="$ex" :index="$index" :key="'ex-' . $index . '-' . count($ex['sets'])" />
        @endforeach

        <button wire:click="$set('showExerciseModal', true)"
            class="w-full py-10 border-2 border-dashed border-slate-900 rounded-3xl flex flex-col items-center justify-center text-slate-600 active:bg-slate-900/50 transition-all">
            <svg class="w-10 h-10 mb-2 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Aggiungi Esercizio</span>
        </button>
    </div>

    @if ($showExerciseModal)
        <div class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md overflow-y-auto">
            <div class="p-4">
                <div class="flex justify-between items-center mb-4 sticky top-0 bg-black/80 py-4 z-50">
                    <h2 class="text-2xl font-black uppercase italic tracking-tighter text-white">Seleziona Esercizio
                    </h2>
                    <button wire:click="$set('showExerciseModal', false)"
                        class="text-yellow-500 font-bold uppercase text-xs">Chiudi</button>
                </div>
                <livewire:pages.exercises />
            </div>
        </div>
    @endif
</div>
