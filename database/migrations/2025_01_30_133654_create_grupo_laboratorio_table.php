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
        Schema::create('grupo_laboratorio', function (Blueprint $table) {
            $table->id();  // Clave primaria
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');  // Relación con la tabla 'grupos'
            $table->foreignId('laboratorio_id')->unsigned();  // Relación con la tabla 'laboratorios'
            $table->timestamps();  // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_laboratorio');
    }
};
