<?php
use function Livewire\Volt\{state, on};

state([
    'show' => false,
    'title' => '',
    'description' => '',
    'action' => '', // Il metodo da chiamare nel padre
    'id' => null, // L'ID dell'elemento da colpire
]);

on([
    'open-modal-confirm' => function ($data) {
        $this->show = true;
        $this->title = $data['title'] ?? 'Sei sicuro?';
        $this->description = $data['description'] ?? 'L\'azione è irreversibile.';
        $this->action = $data['action'];
        $this->id = $data['id'] ?? null;
    },
]);

$confirm = function () {
    // Chiama la funzione nel componente genitore (app-shell o altro)
    $this->dispatch($this->action, id: $this->id);
    $this->show = false;
};
?>

<div>
    @if ($show)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-6 backdrop-blur-sm bg-black/60">
            <div class="bg-slate-900 border border-slate-800 p-6 rounded-3xl w-full max-w-sm shadow-2xl">
                <h3 class="text-xl font-black uppercase italic tracking-tighter mb-2 text-white">{{ $title }}</h3>
                <p class="text-slate-400 text-sm mb-6">{{ $description }}</p>

                <div class="flex gap-3">
                    <button wire:click="$set('show', false)"
                        class="flex-1 py-3 rounded-xl bg-slate-800 text-[10px] font-bold uppercase tracking-widest text-white">Annulla</button>
                    <button wire:click="confirm"
                        class="flex-1 py-3 rounded-xl bg-red-600 text-black text-[10px] font-black uppercase tracking-widest shadow-[0_0_15px_rgba(220,38,38,0.4)]">Conferma</button>
                </div>
            </div>
        </div>
    @endif
</div>
