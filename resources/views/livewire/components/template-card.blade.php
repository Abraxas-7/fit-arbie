<?php
use function Livewire\Volt\{state};

state(['template']);

?>

<div class="bg-slate-900 border border-slate-800 rounded-3xl p-5 shadow-xl relative group">
    {{-- TASTO ELIMINA --}}
    <button
        wire:click="$dispatch('open-modal-confirm', { data: { 
            title: 'Elimina Template', 
            description: 'Vuoi davvero cancellare {{ addslashes($template->name) }}?', 
            action: 'delete-template', 
            id: {{ $template->id }} 
        }})"
        class="absolute top-4 right-4 text-slate-600 hover:text-red-500 transition-colors p-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>

    <div class="flex justify-between items-start mb-4">
        <div>
            <h2 class="text-xl font-black uppercase italic tracking-tighter text-white">{{ $template->name }}</h2>
            <div class="flex gap-2 mt-1">
                <span
                    class="text-[10px] bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 px-2 py-0.5 rounded uppercase font-bold">
                    {{ $template->exercises->count() }} Esercizi
                </span>
            </div>
        </div>
    </div>

    <div class="space-y-1 mb-6">
        @foreach ($template->exercises->take(3) as $te)
            <div class="text-slate-400 text-sm flex items-center gap-2">
                <div class="w-1 h-1 bg-yellow-500 rounded-full"></div>
                {{ $te->exercise->name }}
            </div>
        @endforeach

        @if ($template->exercises->count() > 3)
            <div class="text-slate-600 text-[10px] font-bold uppercase pl-3">
                + altri {{ $template->exercises->count() - 3 }}
            </div>
        @endif
    </div>

    <button
        class="w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 rounded-2xl uppercase tracking-widest text-xs transition-all active:scale-95 shadow-[0_10px_20px_rgba(234,179,8,0.2)]">
        Start Workout
    </button>
</div>
