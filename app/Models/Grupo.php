<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = [ 'nombre']; // Ajusta según tu base de datos

    // Relación: Un grupo tiene muchos laboratorios
    public function laboratorios()
{
    return $this->belongsToMany(Laboratorio::class, 'grupo_laboratorio', 'grupo_id', 'laboratorio_id');
}
public function centrosMedicos()
{
    return $this->belongsToMany(CentroMedico::class, 'centro_medico_grupo', 'grupo_id', 'centro_medico_id');
}
}
