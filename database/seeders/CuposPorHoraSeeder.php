<?php

namespace Database\Seeders;

use App\Models\CuposPorHora;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CuposPorHoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear cupos para el 15 de enero de 2025
        CuposPorHora::create([
            'fecha' => '2025-01-15',
            'hora_inicio' => '07:00:00',
            'total_cupos' => 150,
            'cupos_disponibles' => 150,
        ]);

        CuposPorHora::create([
            'fecha' => '2025-01-15',
            'hora_inicio' => '08:00:00',
            'total_cupos' => 100,
            'cupos_disponibles' => 100,
        ]);

        CuposPorHora::create([
            'fecha' => '2025-01-15',
            'hora_inicio' => '09:00:00',
            'total_cupos' => 80,
            'cupos_disponibles' => 80,
        ]);

        // Puedes añadir más registros si lo necesitas
    }
}
