<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSet extends Model
{
    protected $fillable = [
        'workout_exercise_id',
        'set_number',
        'weight',
        'reps',
        'type',
        'is_completed',
        'rest_time',
        'note'
    ];

    public function workoutExercise()
    {
        return $this->belongsTo(WorkoutExercise::class);
    }
}
