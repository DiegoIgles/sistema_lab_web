<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratorioCentroMedico extends Model
{
    use HasFactory;

    // Si la tabla intermedia tiene un nombre diferente, defínelo aquí
    protected $table = 'laboratorio_centromedico';

    // Si la tabla tiene claves compuestas o no usa los timestamps, puedes deshabilitarlo
    public $timestamps = true;

    // Definir los atributos que se pueden asignar masivamente
    protected $fillable = [
        'laboratorio_id',  // Relación con Laboratorio
        'centros_medicos_id' // Relación con CentroMedico
    ];
}
