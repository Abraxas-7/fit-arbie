<?php
use function Livewire\Volt\{state, on};
use App\Models\Template;

state([
    'currentTab' => 'training',
    'templates' => fn() => Template::where('user_id', auth()->id())
        ->with('exercises.exercise')
        ->latest()
        ->get(),
]);

on([
    // Quando un template viene salvato, la Shell non deve fare nulla sui dati,
    // perché ci pensa il componente Training a ricaricarsi.
    'template-saved' => function () {},

    'set-tab' => function ($tab) {
        $this->currentTab = $tab;
    },

    // La Shell si occupa solo dell'azione distruttiva sul DB
    'delete-template' => function ($id) {
        \App\Models\Template::find($id)?->delete();
    },
]);

$setTab = function ($tab) {
    $this->currentTab = $tab;
};
?>

<div class="min-h-screen bg-black text-white">
    {{-- CONTENUTO DINAMICO --}}
    <main class="pb-24">
        @if ($currentTab === 'training')
            <livewire:pages.training :templates="$templates" />
        @elseif ($currentTab === 'create-template')
            <livewire:pages.create-template />
        @elseif ($currentTab === 'exercises')
            <livewire:pages.exercises />
        @elseif ($currentTab === 'profile')
            <div class="p-10 text-center text-slate-500 uppercase font-black">Profilo in arrivo</div>
        @elseif ($currentTab === 'history')
            <div class="p-10 text-center text-slate-500 uppercase font-black">Storico allenamenti</div>
        @endif
    </main>

    {{-- NAV BAR (Rimessa qui così non scompare mai) --}}
    <nav
        class="fixed bottom-0 left-0 right-0 bg-slate-900/90 backdrop-blur-lg border-t border-slate-800 px-2 pb-safe z-50">
        <div class="flex justify-between items-center h-16 max-w-md mx-auto">

            {{-- Profilo --}}
            <button wire:click="setTab('profile')"
                class="flex flex-col items-center flex-1 {{ $currentTab === 'profile' ? 'text-yellow-500' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-[10px] font-bold uppercase">Profilo</span>
            </button>

            {{-- Storico --}}
            <button wire:click="setTab('history')"
                class="flex flex-col items-center flex-1 {{ $currentTab === 'history' ? 'text-yellow-500' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[10px] font-bold uppercase">Storico</span>
            </button>

            {{-- Training (Tasto centrale) --}}
            <div class="flex-1 flex justify-center -mt-10">
                <button wire:click="setTab('training')"
                    class="p-4 rounded-full shadow-[0_0_20px_rgba(234,179,8,0.4)] border-4 border-black active:scale-90 transition-transform {{ $currentTab === 'training' || $currentTab === 'create-template' ? 'bg-yellow-400 text-black' : 'bg-yellow-500 text-black' }}">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" />
                    </svg>
                </button>
            </div>

            {{-- Esercizi --}}
            <button wire:click="setTab('exercises')"
                class="flex flex-col items-center flex-1 {{ $currentTab === 'exercises' ? 'text-yellow-500' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-[10px] font-bold uppercase">Esercizi</span>
            </button>

            {{-- Social --}}
            <button wire:click="setTab('social')"
                class="flex flex-col items-center flex-1 {{ $currentTab === 'social' ? 'text-yellow-500' : 'text-slate-400' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-[10px] font-bold uppercase">Social</span>
            </button>

        </div>
    </nav>

    {{-- Modale di conferma --}}
    <livewire:components.modal-confirm />
</div>
