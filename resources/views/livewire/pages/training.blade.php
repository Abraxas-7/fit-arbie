<?php

use function Livewire\Volt\{state, on, mount};
use App\Models\Template;

state(['templates' => []]);

// Usiamo una funzione anonima, ma per farla funzionare con Volt
// il modo più pulito è chiamare la logica direttamente dove serve
// o usare questa sintassi:

$loadTemplates = function ($component) {
    $component->templates = Template::where('user_id', auth()->id())
        ->with('exercises.exercise')
        ->latest()
        ->get();
};

mount(function () use ($loadTemplates) {
    // Passiamo $this alla funzione
    $loadTemplates($this);
});

on([
    'template-saved' => function () use ($loadTemplates) {
        $loadTemplates($this);
    },
    'delete-template' => function () use ($loadTemplates) {
        $loadTemplates($this);
    },
]);

?>

<div class="p-4 pb-28">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black uppercase tracking-tighter italic text-white">Training</h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Scegli il tuo workout</p>
        </div>

        <button wire:click="$dispatch('set-tab', { tab: 'create-template' })"
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
                <p class="text-slate-500 font-medium italic">Non hai ancora nessuna scheda.</p>

                <button wire:click="$dispatch('set-tab', { tab: 'create-template' })"
                    class="text-yellow-500 font-black text-xs mt-3 uppercase tracking-widest active:scale-95 transition-transform">
                    Creane una ora
                </button>
            </div>
        @endforelse
    </div>
</div>
