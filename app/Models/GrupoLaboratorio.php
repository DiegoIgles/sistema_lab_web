<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoLaboratorio extends Model
{

    // Definir la tabla de la relación
    protected $table = 'grupo_laboratorio';

    // Relación: Un grupo-laboratorio pertenece a un laboratorio y un grupo
    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class, 'laboratorio_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
}
