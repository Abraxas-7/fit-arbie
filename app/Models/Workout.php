<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = ['user_id', 'template_id', 'name', 'started_at', 'completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function workoutExercises()
    {
        return $this->hasMany(WorkoutExercise::class)->orderBy('position');
    }
}
