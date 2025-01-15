<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SolServicio extends Model
{
    use HasApiTokens;
/**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'solservicio';

    /**
     * Los campos que se pueden asignar de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'afiliado_id',
    ];

    /**
     * Relación de "muchos a uno" con el modelo Afiliado.
     *
     *
     */
    public function afiliado()
    {
        return $this->belongsTo(Afiliado::class, 'afiliado_id');
    }
}
