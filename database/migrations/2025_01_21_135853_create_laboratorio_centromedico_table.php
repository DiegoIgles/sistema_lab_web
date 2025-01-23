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
        Schema::create('laboratorio_centromedico', function (Blueprint $table) {
            $table->id(); // Clave primaria de la tabla intermedia
            $table->bigInteger('laboratorio_id')->unsigned(); // Columna laboratorio_id
            $table->bigInteger('centros_medicos_id')->unsigned(); // Columna centro_medico_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorio_centromedico');
    }
};
