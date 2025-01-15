<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cupos_por_hora', function (Blueprint $table) {
            $table->id();
            $table->date('fecha'); // Fecha de los cupos (formato YYYY-MM-DD)
            $table->time('hora_inicio'); // Hora de inicio del rango (formato HH:MM)
            $table->integer('total_cupos'); // Total de cupos disponibles
            $table->integer('cupos_disponibles'); // Cupos disponibles (esto se va actualizando)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupos_por_hora');
    }
};
