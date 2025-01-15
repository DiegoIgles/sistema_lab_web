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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con la tabla de usuarios
            $table->date('fecha'); // Fecha de la cita
            $table->time('hora'); // Hora de la cita
            $table->enum('estado', ['confirmada', 'pendiente', 'cancelada'])->default('pendiente'); // Estado de la cita
            $table->enum('tipo', ['hemograma', 'heces', 'urianalisis', 'perfil lipido']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
