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

    public function show(Request $request){

        // Buscar los servicios relacionados con el 'solservicio_id'
        $servicioslab = SolServicioLab::where('solservicio_id', $request->solservicio_id)->get();

        // Si no hay servicios para el afiliado dado, devolver un mensaje adecuado
        if ($servicioslab->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron servicios para este afiliado.'
            ], 404);
        }

        // Agregar nombre del laboratorio a cada servicio
        $servicioslabConNombre = $servicioslab->map(function ($servicioLab) {
            $laboratorio = Laboratorio::find($servicioLab->laboratorio_id);
            $servicioLab->laboratorio_nombre = $laboratorio ? $laboratorio->nombre : 'Desconocido';
            return $servicioLab;
        });

        // Retornar los servicios encontrados en formato JSON
        return response()->json($servicioslabConNombre, 200);
   }
}
