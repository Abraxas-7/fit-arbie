<?php
use function Livewire\Volt\{state};

state(['exercise']);

$selectExercise = function () {
    // Comunichiamo a chiunque stia ascoltando che questo esercizio è stato scelto
    $this->dispatch('exercise-selected', exerciseId: $this->exercise->id);
};
?>

<div wire:click="selectExercise"
    class="bg-gray-50 p-4 rounded-2xl border border-gray-100 active:bg-gray-200 cursor-pointer transition-all hover:border-yellow-500 shadow-sm group">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800 group-active:text-yellow-600">{{ $exercise->name }}</h3>
            <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider">
                {{ $exercise->category }}
            </span>
        </div>
        <div class="text-gray-300 group-hover:text-yellow-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </div>
    </div>
</div>
