<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class SolServicioLab extends Model
{
    use HasApiTokens;
    /**
         * Nombre de la tabla asociada al modelo.
         *
         * @var string
         */
        protected $table = 'solservicio_laboratorio';

        /**
         * Los campos que se pueden asignar de forma masiva.
         *
         * @var array
         */
        protected $fillable = [
            'id',
            'solservicio_id',
            'laboratorio_id',
        ];

        /**
     * Relación de "muchos a uno" con el modelo SolServicio.
     */
    public function solServicio()
    {
        return $this->belongsTo(SolServicio::class, 'solservicio_id', 'id');
    }
    /**
     * Relación de "muchos a uno" con el modelo Laboratorio.
     */
    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class, 'laboratorio_id', 'id');
    }
}
