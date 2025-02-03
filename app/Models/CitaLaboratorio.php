<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaLaboratorio extends Model
{
    use HasFactory;

    protected $table = 'citas_laboratorio';

    // Definir las columnas de fecha
    protected $dates = ['fecha'];

    protected $fillable = [
        'centro_medico_id',
        'grupo_id',
        'fecha',
        'hora',
        'cupos_disponibles'
    ];

    public function centroMedico()
    {
        return $this->belongsTo(CentroMedico::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class);
    }
}
