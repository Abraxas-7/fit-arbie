<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;
use App\Models\Exercise;
use App\Models\User;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Recuperiamo il tuo utente (assumendo sia il primo)
        $user = User::first();
        if (!$user) return;

        // --- TEMPLATE PUSH (Petto/Spalle) ---
        $push = Template::create([
            'user_id' => $user->id,
            'name' => 'Push Day - Forza',
        ]);

        $panca = Exercise::where('name', 'Panca Piana Bilanciere')->first();
        $military = Exercise::where('name', 'Military Press')->first();

        // Aggiungiamo Panca Piana
        $te1 = $push->exercises()->create([
            'exercise_id' => $panca->id,
            'position' => 1,
        ]);

        // 3 serie da 5 colpi
        foreach (range(1, 3) as $set) {
            $te1->sets()->create([
                'set_number' => $set,
                'target_reps' => 5,
                'target_weight' => 80,
                'rest_time' => 180
            ]);
        }

        // --- TEMPLATE PULL (Dorso) ---
        $pull = Template::create([
            'user_id' => $user->id,
            'name' => 'Pull Day - Volume',
        ]);

        $stacco = Exercise::where('name', 'Stacco da Terra')->first();

        $te2 = $pull->exercises()->create([
            'exercise_id' => $stacco->id,
            'position' => 1,
        ]);

        $te2->sets()->create([
            'set_number' => 1,
            'target_reps' => 5,
            'target_weight' => 120,
            'rest_time' => 300
        ]);
    }
}
