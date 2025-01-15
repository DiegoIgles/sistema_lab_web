<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\CuposPorHora;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los usuarios
        $users = User::all();

        // Obtener los cupos por hora
        $cupos = CuposPorHora::all();

        // Crear citas de ejemplo
        foreach ($users as $user) {
            // Elegir un cupo aleatorio (solo si hay cupos disponibles)
            $cupo = $cupos->random();
            if ($cupo->cupos_disponibles > 0) {
                Cita::create([
                    'user_id' => $user->id,
                    'fecha' => $cupo->fecha,
                    'hora' => $cupo->hora_inicio,
                    'estado' => 'pendiente', // O 'confirmada' dependiendo del caso
                    'tipo' => 'hemograma',
                ]);

                // Reducir los cupos disponibles
                $cupo->cupos_disponibles--;
                $cupo->save();
            }
        }
    }
}
