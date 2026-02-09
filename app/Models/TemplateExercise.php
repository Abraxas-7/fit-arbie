<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateExercise extends Model
{
    protected $fillable = ['template_id', 'exercise_id', 'position', 'superset_id'];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function sets()
    {
        return $this->hasMany(TemplateSet::class);
    }
}
