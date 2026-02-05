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

<div>
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
                                <div
                                    class="bg-gray-50 p-4 rounded-2xl border border-gray-100 active:bg-gray-200 transition-colors">
                                    <h3 class="font-bold text-gray-800">{{ $exercise->name }}</h3>
                                    <span
                                        class="text-[10px] font-black uppercase text-gray-400">{{ $exercise->category }}</span>
                                </div>
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

        <div
            class="w-8 flex flex-col justify-center items-center sticky top-0 h-screen text-[10px] font-bold text-gray-400 bg-gray-50/50 backdrop-blur-sm border-l border-gray-100">
            @foreach ($this->alphabet as $l)
                <a href="#letter-{{ $l }}"
                    class="w-full text-center py-1 hover:text-blue-600 active:scale-150 transition-transform {{ isset($this->groupedExercises[$l]) ? 'text-blue-600 font-black' : 'opacity-20 pointer-events-none' }}">
                    {{ $l }}
                </a>
            @endforeach
        </div>

    </div>
</div>
