<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeasurement extends Model
{
    protected $fillable = ['user_id', 'type', 'value', 'unit', 'measured_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
