<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroMedicoGrupo extends Model
{
    use HasFactory;

    protected $table = 'centro_medico_grupo';

    // Si tienes campos adicionales en la tabla intermedia, puedes agregarlos a la propiedad fillable
    protected $fillable = ['centro_medico_id', 'grupo_id'];

    // Relación con CentroMedico
    public function centroMedico()
    {
        return $this->belongsTo(CentroMedico::class, 'centro_medico_id');
    }

    // Relación con Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
}
