<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Laboratorio extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'laboratorio';

    /**
     * Los campos que se pueden asignar de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'nombre',

    ];
}
