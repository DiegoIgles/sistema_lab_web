<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recomendacion extends Model
{
    use HasFactory;

    protected $table = 'recomendaciones';

    protected $fillable = ['grupo_id', 'descripcion', 'laboratorio_id','tipo'];

    public function grupo() {
        return $this->belongsTo(Grupo::class);
    }
    public function laboratorio()
{
    return $this->belongsTo(Laboratorio::class);
}
}
