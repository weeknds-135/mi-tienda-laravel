<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // Nombre de nuestra tabla en pgAdmin

    protected $fillable = [
        'username',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
    ];
}