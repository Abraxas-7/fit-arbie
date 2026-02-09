<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSet extends Model
{
    protected $fillable = [
        'template_exercise_id',
        'set_number',
        'type',
        'target_reps',
        'target_weight',
        'rest_time',
        'side',
        'rest_unilateral'
    ];

    public function templateExercise()
    {
        return $this->belongsTo(TemplateExercise::class);
    }
}
