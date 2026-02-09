<?php
use function Livewire\Volt\{state, on};

state(['templates']);

// ASCOLTA l'evento dal padre per aggiornare la lista locale
on([
    'template-saved' => function () {
        // Ricarichiamo i dati qui dentro per sincronizzarli
        $this->templates = \App\Models\Template::where('user_id', auth()->id())
            ->with('exercises.exercise')
            ->latest()
            ->get();
    },
    'delete-template' => function () {
        // Facciamo lo stesso dopo l'eliminazione
        $this->templates = \App\Models\Template::where('user_id', auth()->id())
            ->with('exercises.exercise')
            ->latest()
            ->get();
    },
]);

?>

<div class="p-4 pb-28">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black uppercase tracking-tighter italic text-white">Training</h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Scegli il tuo workout</p>
        </div>

        <button wire:click="$parent.setTab('create-template')"
            class="bg-slate-800 p-2 rounded-xl border border-slate-700 shadow-[0_0_15px_rgba(234,179,8,0.1)] active:scale-90 transition-transform">
            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>

    <div class="grid gap-6">
        @forelse ($templates as $template)
            <livewire:components.template-card :template="$template" :key="$template->id" />
        @empty
            <div class="text-center py-20 border-2 border-dashed border-slate-800 rounded-3xl">
                <p class="text-slate-500 font-medium">Non hai ancora nessuna scheda.</p>
                <button wire:click="$parent.setTab('create-template')"
                    class="text-yellow-500 font-bold text-xs mt-2 uppercase">Creane una ora</button>
            </div>
        @endforelse
    </div>
</div>
