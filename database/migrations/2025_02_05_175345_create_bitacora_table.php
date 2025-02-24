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
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('accion'); // Acción: 'Asociación creada' o 'Asociación eliminada'
            $table->string('nombre_laboratorio'); // Nombre del laboratorio (grupo)
            $table->string('nombre_centro_medico'); // Nombre del centro médico
            $table->timestamp('fecha_accion')->useCurrent(); // Fecha y hora de la acción
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};
