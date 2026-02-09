<?php
use function Livewire\Volt\{state};

state(['alphabet', 'groupedExercises']);
?>

<div
    class="w-8 flex flex-col justify-center items-center sticky top-0 h-screen text-[10px] font-bold text-gray-400 bg-gray-50/50 backdrop-blur-sm border-l border-gray-100">
    @foreach ($alphabet as $l)
        <a href="#letter-{{ $l }}"
            class="w-full text-center py-1 hover:text-blue-600 active:scale-150 transition-transform {{ isset($groupedExercises[$l]) ? 'text-blue-600 font-black' : 'opacity-20 pointer-events-none' }}">
            {{ $l }}
        </a>
    @endforeach
</div>
