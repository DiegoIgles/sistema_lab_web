<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroMedico extends Model
{
    use HasFactory;

    protected $table = 'centros_medicos'; // Nombre de la tabla asociada

    /**
     * Relación muchos a muchos con los laboratorios
     *
     *
     */
     protected $fillable = [
        'nombre',      // Aquí agregas el campo 'nombre'
        'direccion',   // Si tienes otros campos, agrégalos aquí también
    ];
    public function laboratorios()
    {
        return $this->belongsToMany(Laboratorio::class, 'laboratorio_centromedico', 'centros_medicos_id', 'laboratorio_id');
    }
    public function grupos()
{
    return $this->belongsToMany(Grupo::class, 'centro_medico_grupo', 'centro_medico_id', 'grupo_id');
}
}
