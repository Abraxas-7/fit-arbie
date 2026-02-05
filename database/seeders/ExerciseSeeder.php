<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [
            // PETTO
            ['name' => 'Panca Piana Bilanciere', 'category' => 'Petto', 'is_trackable_pr' => true],
            ['name' => 'Panca Inclinata Manubri', 'category' => 'Petto', 'is_trackable_pr' => false],
            ['name' => 'Croci ai Cavi', 'category' => 'Petto', 'is_trackable_pr' => false],

            // DORSO
            ['name' => 'Stacco da Terra', 'category' => 'Dorso', 'is_trackable_pr' => true],
            ['name' => 'Trazioni alla Sbarra', 'category' => 'Dorso', 'is_trackable_pr' => true],
            ['name' => 'Rematore Bilanciere', 'category' => 'Dorso', 'is_trackable_pr' => false],

            // GAMBE
            ['name' => 'Squat High Bar', 'category' => 'Gambe', 'is_trackable_pr' => true],
            ['name' => 'Leg Press 45°', 'category' => 'Gambe', 'is_trackable_pr' => false],
            ['name' => 'Leg Curl Sdraiato', 'category' => 'Gambe', 'is_trackable_pr' => false],

            // SPALLE
            ['name' => 'Military Press', 'category' => 'Spalle', 'is_trackable_pr' => true],
            ['name' => 'Alzate Laterali', 'category' => 'Spalle', 'is_trackable_pr' => false],
        ];

        foreach ($exercises as $ex) {
            Exercise::updateOrCreate(['name' => $ex['name']], $ex);
        }
    }
}
