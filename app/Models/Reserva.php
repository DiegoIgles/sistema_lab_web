<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = ['afiliado_id', 'cita_id', 'telefono']; // Añadido 'telefono'

    public function afiliado()
    {
        return $this->belongsTo(Afiliado::class, 'afiliado_id');
    }

    public function cita()
    {
        return $this->belongsTo(CitaLaboratorio::class);
    }


}
