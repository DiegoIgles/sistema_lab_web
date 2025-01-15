<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Afiliado extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'afiliado';

    /**
     * Los campos que se pueden asignar de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'matricula',
        'paterno',
        'materno',
        'nombre',
    ];

    public function solServicios()
{
    return $this->hasMany(SolServicio::class, 'afiliado_id'); // 'afiliado_id' es la clave foránea en la tabla 'solservicio'
}
}
