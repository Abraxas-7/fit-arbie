<?php

use function Livewire\Volt\{state};

state(['ex', 'index']);

?>

<div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-xl">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-black text-yellow-500 uppercase text-sm tracking-tight italic">
            {{ $ex['name'] }}
        </h3>
        <button wire:click="$parent.removeExercise({{ $index }})" class="text-slate-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="space-y-2 mb-4">
        @foreach ($ex['sets'] as $setIndex => $set)
            <div class="grid grid-cols-12 gap-2 items-center bg-black/30 p-2 rounded-xl border border-slate-800/50">
                <div class="col-span-1 text-[10px] font-bold text-slate-600 text-center">
                    {{ $setIndex + 1 }}
                </div>

                <div class="col-span-4">
                    <select wire:model="selectedExercises.{{ $index }}.sets.{{ $setIndex }}.type"
                        class="w-full bg-slate-800 border-none rounded-lg text-[10px] font-bold py-1 focus:ring-yellow-500 uppercase text-white">
                        <option value="normal">Normal</option>
                        <option value="warmup">Warmup</option>
                        <option value="failure">Failure</option>
                    </select>
                </div>

                <div class="col-span-3">
                    <input type="number"
                        wire:model="selectedExercises.{{ $index }}.sets.{{ $setIndex }}.reps"
                        class="w-full bg-slate-800 border-none rounded-lg text-center text-xs font-bold py-1 focus:ring-yellow-500 text-white">
                </div>

                <div class="col-span-3">
                    <input type="number"
                        wire:model="selectedExercises.{{ $index }}.sets.{{ $setIndex }}.weight"
                        class="w-full bg-slate-800 border-none rounded-lg text-center text-xs font-bold py-1 focus:ring-yellow-500 text-white">
                </div>

                <div class="col-span-1 text-right">
                    <button wire:click="$parent.removeSet({{ $index }}, {{ $setIndex }})"
                        class="text-slate-700 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <button wire:click="$parent.addSet({{ $index }})"
        class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-slate-400 text-[10px] font-black uppercase rounded-xl transition-all border border-slate-700">
        + Aggiungi Serie
    </button>
</div>
