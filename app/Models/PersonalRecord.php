<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalRecord extends Model
{
    protected $fillable = ['user_id', 'exercise_id', 'weight', 'achieved_at'];
    // Relazione con l'utente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con l'esercizio (per sapere se è Panca, Squat, ecc.)
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
