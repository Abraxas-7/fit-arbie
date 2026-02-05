<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'category',
        'unit',
        'is_bodyweight',
        'is_assisted',
        'is_unilateral',
        'is_trackable_pr',
        'description'
    ];
    public function sets()
    {
        return $this->hasMany(WorkoutSet::class);
    }
}
