<?php
use function Livewire\Volt\{state, computed, usesPagination};
use App\Models\Workout;

usesPagination();

$history = computed(function () {
    return Workout::where('user_id', auth()->id())
        ->whereNotNull('completed_at')
        ->with('workoutExercises.exercise')
        ->latest('completed_at')
        ->paginate(10);
});
?>

<div class="p-4 space-y-6 pb-32 animate-in fade-in duration-500">
    {{-- HEATMAP --}}
    <livewire:components.history-heatmap :days="28" />

    {{-- LISTA --}}
    <div class="space-y-4">
        <h2 class="text-xl font-black uppercase italic tracking-tighter text-white px-1">Diario</h2>

        @forelse ($this->history as $workout)
            {{-- Componente Card --}}
            <livewire:components.history-card :workout="$workout" :key="$workout->id" />
        @empty
            <div class="text-center py-20 bg-slate-900/20 rounded-3xl border border-dashed border-slate-800">
                <p class="text-slate-600 font-bold uppercase text-[10px] tracking-[0.2em]">Nessun record trovato</p>
            </div>
        @endforelse

        {{-- PAGINAZIONE --}}
        @if ($this->history->hasMorePages())
            <button wire:click="nextPage"
                class="w-full py-6 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 hover:text-yellow-500 transition-all">
                Carica altro storico
            </button>
        @endif
    </div>
</div>
