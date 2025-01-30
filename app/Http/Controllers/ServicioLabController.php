<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\SolServicioLab;
use Illuminate\Http\Request;

class ServicioLabController extends Controller
{
    public function index(){
            $servicioslab = SolServicioLab::all(); // Recupera todos los registros
            return response()->json($servicioslab);

    }

    public function show(Request $request)
    {
        // Buscar los servicios relacionados con el 'solservicio_id'
        $servicioslab = SolServicioLab::where('solservicio_id', $request->solservicio_id)->get();

        // Si no hay servicios para el solservicio_id dado, devolver un mensaje adecuado
        if ($servicioslab->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron servicios para este solservicio.'
            ], 404);
        }

        // Agregar nombre del laboratorio y el grupo a cada servicio
        $servicioslabConGrupo = $servicioslab->map(function ($servicioLab) {
            $laboratorio = Laboratorio::find($servicioLab->laboratorio_id);
            $grupo = $laboratorio ? $laboratorio->obtenerGrupo() : null;

            $servicioLab->laboratorio_nombre = $laboratorio ? $laboratorio->nombre : 'Desconocido';
            $servicioLab->grupo_nombre = $grupo ? $grupo->nombre : 'Sin Grupo';

            return $servicioLab;
        });

        // Retornar los servicios encontrados en formato JSON
        return response()->json($servicioslabConGrupo, 200);
    }
}
