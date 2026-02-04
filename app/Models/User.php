<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // campi assegnabili
    protected $fillable = [
        'username',
        'password',
    ];

    // campi nascosti per array/json
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // casting
    protected $casts = [];
}
