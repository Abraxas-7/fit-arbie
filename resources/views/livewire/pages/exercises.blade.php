<?php
use App\Models\Exercise;
use function Livewire\Volt\{state, computed};

state(['search' => '']);

$groupedExercises = computed(function () {
    return Exercise::where('name', 'like', '%' . $this->search . '%')
        ->orderBy('name')
        ->get()
        ->groupBy(fn($item) => strtoupper(substr($item->name, 0, 1)));
});

$alphabet = computed(fn() => range('A', 'Z'));
?>

<div class="relative flex bg-white min-h-screen">
    <div class="flex-1 pb-24">
        <div class="p-4 bg-white sticky top-0 z-50 border-b">
            <input wire:model.live="search" type="text" placeholder="Cerca esercizio..."
                class="w-full bg-gray-100 border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="px-4">
            @forelse($this->groupedExercises as $letter => $exercises)
                <div id="letter-{{ $letter }}" class="pt-4 scroll-mt-[73px]">
                    <div
                        class="sticky top-[73px] bg-white py-2 text-blue-600 font-black text-xl border-b border-gray-100 mb-2 z-40">
                        {{ $letter }}
                    </div>

                    <div class="space-y-3">
                        @foreach ($exercises as $exercise)
                            {{-- RICHIAMO IL COMPONENTE ATOMICO --}}
                            <livewire:components.exercise-item :exercise="$exercise" :key="$exercise->id" />
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-20 text-gray-400 font-medium">
                    Nessun esercizio trovato...
                </div>
            @endforelse
        </div>
    </div>

    <livewire:components.alphabet-index :alphabet="$this->alphabet" :groupedExercises="$this->groupedExercises->toArray()" />
</div>
