<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuposPorHora extends Model
{
    use HasFactory;

    // Asegúrate de que el nombre de la tabla esté correcto
    protected $table = 'cupos_por_hora';

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'total_cupos',
        'cupos_disponibles',
    ];
    public function ocuparCupo()
    {
        if ($this->cupos_disponibles > 0) {
            $this->cupos_disponibles--;  // Reducir los cupos disponibles en 1
            $this->save();  // Guardar la actualización en la base de datos
            return true;
        }
        return false;  // Retornar false si no hay cupos disponibles
    }
}
