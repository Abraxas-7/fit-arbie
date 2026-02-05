<?php
use function Livewire\Volt\{state};

state(['currentTab' => 'exercises']); // Partiamo da qui per testare subito

$setTab = function ($tab) {
    $this->currentTab = $tab;
};
?>

<div>
    <div class="min-h-screen bg-gray-50">
        @if ($currentTab === 'exercises')
            <livewire:pages.exercises />
        @else
            <div class="p-10 text-center text-gray-400">Pagina in costruzione...</div>
        @endif
    </div>

    <nav class="fixed bottom-0 w-full bg-white border-t h-16 flex justify-around items-center">
        <button wire:click="setTab('training')" class="text-gray-400">Training</button>
        <button wire:click="setTab('exercises')"
            class="{{ $currentTab === 'exercises' ? 'text-blue-600 font-bold' : 'text-gray-400' }}">Esercizi</button>
        <button wire:click="setTab('profile')" class="text-gray-400">Profilo</button>
    </nav>
</div>
